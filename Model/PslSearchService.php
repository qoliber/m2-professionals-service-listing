<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Model;

use Magento\AdvancedSearch\Model\Client\ClientResolver;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Qoliber\Psl\Api\Data\OpenSearchQueryInterface;
use Qoliber\Psl\Api\Data\ProfileInterface;
use Qoliber\Psl\Api\PslSearchInterface;

class PslSearchService implements PslSearchInterface
{
    /**
     * @param \Magento\AdvancedSearch\Model\Client\ClientResolver $clientResolver
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ClientResolver $clientResolver,
        private readonly ScopeConfigInterface $scopeConfig,
    ) {
    }

    /**
     * Search PSL profiles
     *
     * @param \Qoliber\Psl\Api\Data\OpenSearchQueryInterface $filters
     * @return mixed[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function search(OpenSearchQueryInterface $filters): array
    {
        $client = $this->clientResolver->create();

        // @phpstan-ignore-next-line
        if ($client->indexExists($this->scopeConfig->getValue('qoliber_psl/opensearch/index'))) {
            $must[] = ['match' => ['status' => ProfileInterface::STATUS_APPROVED]];

            if ($filters->getLatitude() !== null
                && $filters->getLongitude() !== null
                && $filters->getDistance() !== null) {
                $must[] = [
                    'geo_distance' => [
                        'distance' => $filters->getDistance(),
                        'location' => [
                            'lat' => $filters->getLatitude(),
                            'lon' => $filters->getLongitude()
                        ]
                    ]
                ];
            }

            if (!empty($filters->getCountry())) {
                $must[] = ['match' => ['country' => $filters->getCountry()]];
            }

            if (!empty($filters->getCity())) {
                $must[] = ['match' => ['city' => $filters->getCity()]];
            }

            if (!empty($filters->getServices())) {
                foreach ($filters->getServices() as $service) {
                    $must[] = ['term' => ['services' => $service]];
                }
            }

            if (!empty($filters->getCertificates())) {
                foreach ($filters->getCertificates() as $certificate) {
                    $must[] = [
                        'nested' => [
                            'path' => 'certificates',
                            'query' => [
                                'bool' => [
                                    'must' => [
                                        'term' => ['certificates.certificate_type' => $certificate]
                                    ]
                                ]
                            ]
                        ]
                    ];
                }
            }

            $searchParams = [
                'index' => $this->scopeConfig->getValue('qoliber_psl/opensearch/index'),
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => $must
                        ]
                    ]
                ]
            ];

            // @phpstan-ignore-next-line
            $response = $client->query($searchParams);

            return array_map(fn($hit) => $hit['_source'], $response['hits']['hits']);
        }

        return [];
    }
}
