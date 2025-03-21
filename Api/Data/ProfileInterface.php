<?php
/**
 * Created by Qoliber
 *
 * @category    Qoliber
 * @package     Qoliber_Psl
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\Psl\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ProfileInterface extends ExtensibleDataInterface
{
    /** @var int */
    public const STATUS_PENDING = 1;

    /** @var int */
    public const STATUS_APPROVED = 2;

    /** @var int */
    public const STATUS_REJECTED = 3;

    /** @var int */
    public const STATUS_SUSPENDED = 4;

    /** @var int  */
    public const STATUS_NOT_PUBLISHED = 5;

    /** @var string Profile ID field */
    public const PROFILE_ID = 'profile_id';

    /** @var string Customer ID field */
    public const CUSTOMER_ID = 'customer_id';

    /** @var string */
    public const STATUS = 'status';

    /** @var string Company Name field */
    public const COMPANY_NAME = 'company_name';

    /** @var string City field */
    public const CITY = 'city';

    /** @var string Country field */
    public const COUNTRY = 'country';

    /** @var string Logo field */
    public const LOGO = 'logo';

    /** @var string Homepage URL field */
    public const HOMEPAGE_URL = 'homepage_url';

    /** @var string Full Address field */
    public const FULL_ADDRESS = 'full_address';

    /** @var string Latitude field */
    public const LATITUDE = 'latitude';

    /** @var string Longitude field */
    public const LONGITUDE = 'longitude';

    /** @var string Description field */
    public const SHORT_DESCRIPTION = 'short_description';

    /** @var string Description field */
    public const DESCRIPTION = 'description';

    /** @var string Created At timestamp field */
    public const CREATED_AT = 'created_at';

    /** @var string Updated At timestamp field */
    public const UPDATED_AT = 'updated_at';

    /** @var string Social Media Links field */
    public const SOCIAL_MEDIA_LINKS = 'social_media_links';

    /** @var string Services field */
    public const SERVICES = 'services';

    /** @var string  */
    public const CERTIFICATES = 'certificates';

    /**
     * Get the profile ID.
     *
     * @return string|null
     */
    public function getProfileId(): ?string;

    /**
     * Set the profile ID.
     *
     * @param string $profileId
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setProfileId(string $profileId): self;

    /**
     * Get the customer ID.
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Set the customer ID.
     *
     * @param int $customerId
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setCustomerId(int $customerId): self;

    /**
     * Get the customer ID.
     *
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * Set the customer ID.
     *
     * @param null|int $status
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setStatus(?int $status): self;

    /**
     * Get the company name.
     *
     * @return string|null
     */
    public function getCompanyName(): ?string;

    /**
     * Set the company name.
     *
     * @param string $company_name
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setCompanyName(string $company_name): self;

    /**
     * Get the city.
     *
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * Set the city.
     *
     * @param string $city
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setCity(string $city): self;

    /**
     * Get the country ID.
     *
     * @return string|null
     */
    public function getCountry(): ?string;

    /**
     * Set the country ID.
     *
     * @param string|null $country
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setCountry(?string $country): self;

    /**
     * Get the logo.
     *
     * @return string|null
     */
    public function getLogo(): ?string;

    /**
     * Set the logo.
     *
     * @param string $logo
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setLogo(string $logo): self;

    /**
     * Get the homepage URL.
     *
     * @return string|null
     */
    public function getHomepageUrl(): ?string;

    /**
     * Set the homepage URL.
     *
     * @param string $homepage_url
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setHomepageUrl(string $homepage_url): self;

    /**
     * Get the full address.
     *
     * @return string|null
     */
    public function getFullAddress(): ?string;

    /**
     * Set the full address.
     *
     * @param string $full_address
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setFullAddress(string $full_address): self;

    /**
     * Get the latitude.
     *
     * @return string|null
     */
    public function getLatitude(): ?string;

    /**
     * Set the latitude.
     *
     * @param string|null $latitude
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setLatitude(?string $latitude): self;

    /**
     * Get the longitude.
     *
     * @return string|null
     */
    public function getLongitude(): ?string;

    /**
     * Set the longitude.
     *
     * @param string|null $longitude
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setLongitude(?string $longitude): self;

    /**
     * Get a short description.
     *
     * @return string|null
     */
    public function getShortDescription(): ?string;

    /**
     * Set a description.
     *
     * @param string $shortDescription
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setShortDescription(string $shortDescription): self;

    /**
     * Get a description.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Set a description.
     *
     * @param string $description
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setDescription(string $description): self;

    /**
     * Get the created at timestamp.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Set the created at timestamp.
     *
     * @param string $created_at
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setCreatedAt(string $created_at): self;

    /**
     * Get the updated at timestamp.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Set the updated at timestamp.
     *
     * @param string $updated_at
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setUpdatedAt(string $updated_at): self;

    /**
     * Get social media links.
     *
     * @return string|null
     */
    public function getSocialMediaLinks(): ?string;

    /**
     * Set social media links.
     *
     * @param string $socialMediaLinks
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setSocialMediaLinks(string $socialMediaLinks): self;

    /**
     * Get the services associated with the profile.
     *
     * @return string|null
     */
    public function getServices(): ?string;

    /**
     * Set the services.
     *
     * @param string $services
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setServices(string $services): self;

    /**
     * Get the certificates associated with the profile.
     *
     * @return string|null
     */
    public function getCertificates(): ?string;

    /**
     * Set the certificates.
     *
     * @param null|string $certificates
     * @return \Qoliber\Psl\Api\Data\ProfileInterface
     */
    public function setCertificates(?string $certificates): self;

    /**
     * Get Profile Status Label
     *
     * @return string
     */
    public function getProfileStatusLabel(): string;
}
