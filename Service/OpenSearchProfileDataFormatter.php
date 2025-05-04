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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Qoliber\Psl\Api\Data\ProfileInterface;

class OpenSearchProfileDataFormatter
{
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Qoliber\Psl\Service\PslOrderedItemsProvider $pslOrderedItemsProvider
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly PslOrderedItemsProvider $pslOrderedItemsProvider,
        private readonly TimezoneInterface $timezone
    ) {
    }

    /**
     * Get field value based on feature status
     *
     * @param mixed[] $pslOrderedItems
     * @param string $feature
     * @param mixed $value
     * @param mixed $defaultValue
     * @return mixed
     */
    private function getFieldValue(
        array $pslOrderedItems,
        string $feature,
        mixed $value,
        mixed $defaultValue = null
    ): mixed {
        return $this->isFeatureActive($pslOrderedItems, $feature) ? $value : $defaultValue;
    }

    /**
     * Format Data
     *
     * @param ProfileInterface $profile
     * @return mixed[]
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function format(ProfileInterface $profile): array
    {
        $customerId = (int) $profile->getCustomerId();
        $pslOrderedItems = $this->pslOrderedItemsProvider->getCustomerPslOrderedItems($customerId);

        $data = [
            'customer_id' => $customerId,
            'status' => $profile->getStatus(),
            'company_name' => $profile->getCompanyName(),
            'city' => $profile->getCity(),
            'country' => $profile->getCountry(),
        ];

        if ($this->isFeatureActive($pslOrderedItems, 'geolocation')) {
            $data['location'] = [
                'lat' => (float)$profile->getLatitude(),
                'lon' => (float)$profile->getLongitude(),
            ];
        }

        $data['logo'] = $this->getFieldValue($pslOrderedItems, 'logo', $profile->getLogo());
        $services = $profile->getServices() ? json_decode($profile->getServices(), true) : [];
        $data['services'] = $this->getFieldValue($pslOrderedItems, 'services', $services, []);

        $certificates = $profile->getCertificates() ? json_decode($profile->getCertificates(), true) : [];
        $formattedCertificates = [];
        foreach ($certificates as $name => $count) {
            $formattedCertificates[] = [
                'name' => $name,
                'count' => (int)$count
            ];
        }
        $data['certificates'] = $this->getFieldValue($pslOrderedItems, 'certificates', $formattedCertificates, []);

        $socialMediaLinks = json_decode($profile->getSocialMediaLinks() ?? '{}', true);
        $formattedSocialLinks = array_map(
            fn ($name, $url) => ['name' => $name, 'url' => $url],
            array_keys($socialMediaLinks),
            array_values($socialMediaLinks)
        );
        $data['social_media_links'] = $this->getFieldValue($pslOrderedItems, 'social', $formattedSocialLinks, []);

        $data['full_address'] = $this->getFieldValue($pslOrderedItems, 'content', $profile->getFullAddress());
        $data['short_description'] = $this->getFieldValue($pslOrderedItems, 'content', $profile->getShortDescription());
        $data['description'] = $this->getFieldValue($pslOrderedItems, 'content', $profile->getDescription());

        return [
            'index' => $this->scopeConfig->getValue('qoliber_psl/opensearch/index'),
            'id' => (string)$customerId,
            'body' => $data
        ];
    }

    /**
     * Check if a feature is active and not expired
     *
     * @param mixed[] $pslOrderedItems
     * @param string $feature
     * @return bool
     */
    private function isFeatureActive(array $pslOrderedItems, string $feature): bool
    {
        $sku = 'psl_profile_' . $feature;
        if (empty($pslOrderedItems[$sku])) {
            return false;
        }

        $expiryDate = $this->timezone->date($pslOrderedItems[$sku]);
        $today = $this->timezone->date();

        return $expiryDate > $today;
    }
}
