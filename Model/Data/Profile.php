<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Model\Data;

use Magento\Framework\Model\AbstractModel;
use Qoliber\Psl\Api\Data\ProfileInterface;

class Profile extends AbstractModel implements ProfileInterface
{
    /**
     * Get profile ID
     *
     * @return string|null
     */
    public function getProfileId(): ?string
    {
        return $this->getData(self::PROFILE_ID);
    }

    /**
     * Set profile ID
     *
     * @param string $profileId
     * @return ProfileInterface
     */
    public function setProfileId(string $profileId): ProfileInterface
    {
        return $this->setData(self::PROFILE_ID, $profileId);
    }

    /**
     * Get customer ID
     *
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return (int) $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set customer ID
     *
     * @param int $customerId
     * @return ProfileInterface
     */
    public function setCustomerId(int $customerId): ProfileInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get Status
     *
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return (int) $this->getData(self::STATUS);
    }

    /**
     * Set Status
     *
     * @param int|null $status
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setStatus(?int $status): ProfileInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get company name
     *
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->getData(self::COMPANY_NAME);
    }

    /**
     * Set company name
     *
     * @param string $company_name
     * @return ProfileInterface
     */
    public function setCompanyName(string $company_name): ProfileInterface
    {
        return $this->setData(self::COMPANY_NAME, $company_name);
    }

    /**
     * Get city name
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->getData(self::CITY);
    }

    /**
     * Set city name
     *
     * @param string $city
     * @return ProfileInterface
     */
    public function setCity(string $city): ProfileInterface
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * Get country ID
     *
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * Set country ID
     *
     * @param string|null $country
     * @return ProfileInterface
     */
    public function setCountry(?string $country): ProfileInterface
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * Get logo
     *
     * @return string|null
     */
    public function getLogo(): ?string
    {
        return $this->getData(self::LOGO);
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return ProfileInterface
     */
    public function setLogo(string $logo): ProfileInterface
    {
        return $this->setData(self::LOGO, $logo);
    }

    /**
     * Get homepage URL
     *
     * @return string|null
     */
    public function getHomepageUrl(): ?string
    {
        return $this->getData(self::HOMEPAGE_URL);
    }

    /**
     * Set homepage URL
     *
     * @param string $homepage_url
     * @return ProfileInterface
     */
    public function setHomepageUrl(string $homepage_url): ProfileInterface
    {
        return $this->setData(self::HOMEPAGE_URL, $homepage_url);
    }

    /**
     * Get full address
     *
     * @return string|null
     */
    public function getFullAddress(): ?string
    {
        return $this->getData(self::FULL_ADDRESS);
    }

    /**
     * Set full address
     *
     * @param string $full_address
     * @return ProfileInterface
     */
    public function setFullAddress(string $full_address): ProfileInterface
    {
        return $this->setData(self::FULL_ADDRESS, $full_address);
    }

    /**
     * Get latitude
     *
     * @return string|null
     */
    public function getLatitude(): ?string
    {
        return $this->getData(self::LATITUDE);
    }

    /**
     * Set latitude
     *
     * @param string|null $latitude
     * @return ProfileInterface
     */
    public function setLatitude(?string $latitude): ProfileInterface
    {
        return $this->setData(self::LATITUDE, $latitude);
    }

    /**
     * Get longitude
     *
     * @return string|null
     */
    public function getLongitude(): ?string
    {
        return $this->getData(self::LONGITUDE);
    }

    /**
     * Set longitude
     *
     * @param string|null $longitude
     * @return ProfileInterface
     */
    public function setLongitude(?string $longitude): ProfileInterface
    {
        return $this->setData(self::LONGITUDE, $longitude);
    }

    /**
     * Get short description
     *
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->getData(self::SHORT_DESCRIPTION);
    }

    /**
     * Set short description
     *
     * @param string $shortDescription
     * @return ProfileInterface
     */
    public function setShortDescription(string $shortDescription): ProfileInterface
    {
        return $this->setData(self::SHORT_DESCRIPTION, $shortDescription);
    }

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ProfileInterface
     */
    public function setDescription(string $description): ProfileInterface
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get created at timestamp
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set created at timestamp
     *
     * @param string $created_at
     * @return ProfileInterface
     */
    public function setCreatedAt(string $created_at): ProfileInterface
    {
        return $this->setData(self::CREATED_AT, $created_at);
    }

    /**
     * Get updated at timestamp
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set updated at timestamp
     *
     * @param string $updated_at
     * @return ProfileInterface
     */
    public function setUpdatedAt(string $updated_at): ProfileInterface
    {
        return $this->setData(self::UPDATED_AT, $updated_at);
    }

    /**
     * Get social media links
     *
     * @return string|null
     */
    public function getSocialMediaLinks(): ?string
    {
        return $this->getData(self::SOCIAL_MEDIA_LINKS);
    }

    /**
     * Set social media links
     *
     * @param string $socialMediaLinks
     * @return ProfileInterface
     */
    public function setSocialMediaLinks(string $socialMediaLinks): ProfileInterface
    {
        return $this->setData(self::SOCIAL_MEDIA_LINKS, $socialMediaLinks);
    }

    /**
     * Get services
     *
     * @return string|null
     */
    public function getServices(): ?string
    {
        return $this->getData(self::SERVICES);
    }

    /**
     * Set services
     *
     * @param string $services
     * @return ProfileInterface
     */
    public function setServices(string $services): ProfileInterface
    {
        return $this->setData(self::SERVICES, $services);
    }

    /**
     * Get Certificates
     *
     * @return string|null
     */
    public function getCertificates(): ?string
    {
        return $this->getData(self::CERTIFICATES);
    }

    /**
     * Set Certificates
     *
     * @param null|string $certificates
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setCertificates(?string $certificates): ProfileInterface
    {
        return $this->setData(self::CERTIFICATES, $certificates);
    }

    /**
     * Get Profile Status Label
     *
     * @return string
     */
    public function getProfileStatusLabel(): string
    {
        $statusLabels = [
            ProfileInterface::STATUS_PENDING   => __('Pending')->render(),
            ProfileInterface::STATUS_APPROVED  => __('Approved')->render(),
            ProfileInterface::STATUS_REJECTED  => __('Needs Changes')->render(),
            ProfileInterface::STATUS_SUSPENDED => __('Suspended')->render()
        ];

        return $statusLabels[$this->getStatus()] ?? __('Unknown')->render();
    }
}
