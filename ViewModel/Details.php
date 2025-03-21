<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\ViewModel;

use Magento\Customer\Model\Session;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Qoliber\Psl\Api\Data\ProfileInterface;
use Qoliber\Psl\Api\ProfileRepositoryInterface;

class Details implements ArgumentInterface
{
    /** @var string */
    public const XPATH_PSL_DETAILS_SOCIAL_MEDIA = 'qoliber_psl/details/social_media';

    /** @var string */
    public const XPATH_PSL_DETAILS_CERTIFICATES = 'qoliber_psl/details/certificates';

    /** @var string */
    public const XPATH_PSL_DETAILS_SERVICES = 'qoliber_psl/details/services';

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Qoliber\Psl\Api\ProfileRepositoryInterface $profileRepository
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     */
    public function __construct(
        private readonly StoreManagerInterface $storeManager,
        private readonly Session $customerSession,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly SerializerInterface $serializer,
        private readonly ProfileRepositoryInterface $profileRepository,
        private readonly CollectionFactory $countryCollectionFactory
    ) {
    }

    /**
     * Get Social Media
     *
     * @return mixed[]
     */
    public function getSocialMedia(): array
    {
        $data = $this->serializer->unserialize(
            $this->scopeConfig->getValue(self::XPATH_PSL_DETAILS_SOCIAL_MEDIA) ?? ''
        );

        return is_array($data) ? $data : [];
    }

    /**
     * Get Certificates
     *
     * @return mixed[]
     */
    public function getCertificates(): array
    {
        $data = $this->serializer->unserialize(
            $this->scopeConfig->getValue(self::XPATH_PSL_DETAILS_CERTIFICATES) ?? ''
        );

        return is_array($data) ? $data : [];
    }

    /**
     * Get Services
     *
     * @return mixed[]
     */
    public function getServices(): array
    {
        $data = $this->serializer->unserialize(
            $this->scopeConfig->getValue(self::XPATH_PSL_DETAILS_SERVICES) ?? ''
        );

        return is_array($data) ? $data : [];
    }

    /**
     * Get Customer Psl Profile
     *
     * @param int|null $customerId
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function getCustomerPslProfile(?int $customerId = null): ProfileInterface
    {
        return $this->profileRepository->getByCustomerId(
            $customerId ?? (int) $this->customerSession->getCustomerId()
        );
    }

    /**
     * Get Country Collection
     *
     * @return string[][]
     */
    public function getCountryCollection(): array
    {
        return $this->countryCollectionFactory->create()->loadByStore()->toOptionArray("");
    }

    /**
     * Format Array for JS
     *
     * @param string $jsonEncodedValue
     * @return string
     */
    public function formatJsonOutputForAlpineJS(string $jsonEncodedValue): string
    {
        return str_replace(
            '"',
            "'",
            json_encode(
                json_decode($jsonEncodedValue),
                JSON_UNESCAPED_SLASHES
            ) ?: ''
        );
    }

    /**
     * Get Enabled Values
     *
     * @param string $jsonEncodedValue
     * @return string
     */
    public function getEnabledValues(string $jsonEncodedValue): string
    {
        $decodedValue = json_decode($jsonEncodedValue, true);
        $result = array_values($decodedValue ?? []);

        if (array_keys($decodedValue ?? []) !== range(0, count($decodedValue ?? []) - 1)) {
            $result = array_keys($decodedValue ?? []);
        }

        return str_replace('"', "'", json_encode($result, JSON_UNESCAPED_SLASHES) ?: '');
    }

    /**
     * Get Media URL
     *
     * @param string|null $path
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPslPicture(?string $path) : string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
            . DIRECTORY_SEPARATOR . $path;
    }
}
