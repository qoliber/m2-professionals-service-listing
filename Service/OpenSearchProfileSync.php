<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Service;

use Magento\AdvancedSearch\Model\Client\ClientResolver;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\OpenSearch\Model\OpenSearch;
use Psr\Log\LoggerInterface;

/**
 * Service class responsible for synchronizing PSL profiles with OpenSearch
 */
class OpenSearchProfileSync
{
    /**
     * @param \Magento\AdvancedSearch\Model\Client\ClientResolver $clientResolver
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Qoliber\Psl\Service\OpenSearchProfileDataFormatter $profileDataFormatter
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        private readonly ClientResolver $clientResolver,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly OpenSearchProfileDataFormatter $profileDataFormatter,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Synchronize all PSL profiles with OpenSearch
     *
     * @return void
     */
    public function execute(): void
    {
        try {
            /** @var \Magento\OpenSearch\Model\OpenSearch $openSearch */
            $openSearch = $this->clientResolver->create();
            $this->createIndexIfNotExists($openSearch);
            $this->syncProfiles($openSearch);
        } catch (\Exception $e) {
            $this->logger->error('Error during PSL profile synchronization: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }
    }

    /**
     * Create an OpenSearch index if it doesn't exist
     *
     * @param \Magento\OpenSearch\Model\OpenSearch $openSearch
     * @return void
     */
    private function createIndexIfNotExists(OpenSearch $openSearch): void
    {
        $indexName = $this->scopeConfig->getValue('qoliber_psl/opensearch/index');
        $exists = $openSearch->getOpenSearchClient()->indices()->exists(['index' => $indexName]);

        if (!$exists) {
            $openSearch->getOpenSearchClient()->indices()->create([
                'index' => $indexName,
                'body' => [
                    'mappings' => [
                        'properties' => [
                            'customer_id' => ['type' => 'integer'],
                            'status' => ['type' => 'boolean'],
                            'company_name' => ['type' => 'text'],
                            'logo' => ['type' => 'keyword'],
                            'city' => ['type' => 'text'],
                            'country' => ['type' => 'keyword'],
                            'services' => ['type' => 'keyword'],
                            'location' => ['type' => 'geo_point'],
                            'certificates' => [
                                'type' => 'nested',
                                'properties' => [
                                    'name' => ['type' => 'keyword'],
                                    'count' => ['type' => 'integer']
                                ]
                            ],
                            'social_media_links' => [
                                'type' => 'nested',
                                'properties' => [
                                    'name' => ['type' => 'keyword'],
                                    'url' => ['type' => 'keyword']
                                ]
                            ],
                            'active_features' => ['type' => 'keyword'],
                            'features_expiry' => ['type' => 'object']
                        ]
                    ]
                ]
            ]);
        }
    }

    /**
     * Synchronize profiles with OpenSearch
     *
     * @param \Magento\OpenSearch\Model\OpenSearch $openSearch
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function syncProfiles(OpenSearch $openSearch): void
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $customers = $this->customerRepository->getList($searchCriteria);
        $bulkBody = [];

        foreach ($customers->getItems() as $customer) {
            try {
                $extensionAttributes = $customer->getExtensionAttributes();

                if (!$extensionAttributes || !$extensionAttributes->getPslProfile()) {
                    continue;
                }

                $profile = $extensionAttributes->getPslProfile();
                $indexData = $this->profileDataFormatter->format($profile);

                $bulkBody[] = [
                    'index' => [
                        '_index' => $indexData['index'],
                        '_id' => $indexData['id']
                    ]
                ];
                $bulkBody[] = $indexData['body'];
            } catch (\Exception $e) {
                $this->logger->error(
                    'Error syncing profile for customer ' .
                    $customer->getId() . ': '
                    . $e->getMessage(),
                    [
                        'exception' => $e
                    ]
                );

                continue;
            }
        }

        if (!empty($bulkBody)) {
            try {
                $openSearch->getOpenSearchClient()->bulk(['body' => $bulkBody]);
            } catch (\Exception $e) {
                $this->logger->error('Error bulk indexing profiles: ' . $e->getMessage(), [
                    'exception' => $e
                ]);
            }
        }
    }
}
