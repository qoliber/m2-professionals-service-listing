<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Plugin;

use Magento\AdvancedSearch\Model\Client\ClientResolver;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Qoliber\Psl\Api\Data\ProfileInterface;
use Qoliber\Psl\Api\ProfileRepositoryInterface;

class OpenSearchProfileSync
{
    /**
     * @param \Magento\AdvancedSearch\Model\Client\ClientResolver $clientResolver
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(
        private readonly ClientResolver $clientResolver,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * After Plugin for ProfileRepositoryInterface::save
     *
     * @param \Qoliber\Psl\Api\ProfileRepositoryInterface $subject
     * @param \Qoliber\Psl\Api\Data\ProfileInterface $profile
     *
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterSave(ProfileRepositoryInterface $subject, ProfileInterface $profile): ProfileInterface
    {
        if ($profile->getCustomerId() && $profile->getProfileId()) {
            /** @var \Magento\OpenSearch\Model\OpenSearch $client */
            $client = $this->clientResolver->create();
            $formattedCertificates = [];
            $customerEntity = $this->customerRepository->getById($profile->getCustomerId());

            foreach (json_decode($profile->getCertificates() ?: '') as $certificateType => $count) {
                $formattedCertificates[] = [
                    'certificate_type' => $certificateType,
                    'count' => (int)$count
                ];
            }

            $services = json_decode($profile->getServices() ?: '', true);
            if (!is_array($services)) {
                $services = [];
            }

            /** @var \Magento\Framework\Api\AttributeInterface[] $customAttributes */
            $customAttributes = $customerEntity->getCustomAttributes();
            $data = [
                'customer_id' => $profile->getCustomerId(),
                'status' => $profile->getStatus(),
                'account_type' => $this->getAccountTypeLabel((int)$customAttributes['account_type']->getValue()),
                'company_name' => $profile->getCompanyName(),
                'logo' => $profile->getLogo(),
                'city' => $profile->getCity(),
                'country' => $profile->getCountry(),
                'services' => $services,
                'location' => [
                    'lat' => (float)$profile->getLatitude(),
                    'lon' => (float)$profile->getLongitude(),
                ],
                'certificates' => $formattedCertificates,
                'social_media_links' => array_map(
                    fn($name, $url) => ['name' => $name, 'url' => $url],
                    array_keys(json_decode($profile->getSocialMediaLinks() ?? '{}', true)),
                    array_values(json_decode($profile->getSocialMediaLinks() ?? '{}', true))
                )
            ];

            $client->getOpenSearchClient()->index([
                'index' => $this->scopeConfig->getValue('qoliber_psl/opensearch/index'),
                'id' => (string)$profile->getCustomerId(),
                'body' => $data
            ]);
        }

        return $profile;
    }

    /**
     * Get Account Type
     *
     * @param int $customerAccountType
     * @return string
     */
    private function getAccountTypeLabel(int $customerAccountType): string
    {
        $rawAccountTypes = $this->scopeConfig->getValue('qoliber_psl/settings/account_types');
        $accountTypes = is_string($rawAccountTypes)
            ? (array) $this->serializer->unserialize($rawAccountTypes)
            : [];

        $i = 0;

        foreach ($accountTypes as $accountType) {
            if ($i === $customerAccountType) {
                return $accountType['account_type'];
            }

            $i++;
        }

        return '';
    }
}
