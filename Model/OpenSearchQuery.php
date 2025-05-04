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

use Qoliber\Psl\Api\Data\OpenSearchQueryInterface;

class OpenSearchQuery implements OpenSearchQueryInterface
{
    /** @var string|null */
    private ?string $country = null;

    /** @var string|null */
    private ?string $city = null;

    /** @var string[] */
    private array $services = [];

    /** @var mixed[] */
    private array $certificates = [];

    /** @var float|null */
    private ?float $longitude = null;

    /** @var float|null */
    private ?float $latitude = null;

    /** @var string|null */
    private ?string $getDistance = null;

    /**
     * Get Country
     *
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Set Country
     *
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get City
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Set City
     *
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get Services
     *
     * @return array|string[]
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * Set Services
     *
     * @param string[] $services
     * @return $this
     */
    public function setServices(array $services): self
    {
        $this->services = $services;

        return $this;
    }

    /**
     * Get Certificates
     *
     * @return array|string[]
     */
    public function getCertificates(): array
    {
        return $this->certificates;
    }

    /**
     * Set Certificates
     *
     * @param mixed[] $certificates
     * @return $this
     */
    public function setCertificates(array $certificates): self
    {
        $this->certificates = $certificates;

        return $this;
    }

    /**
     * Get Geo Latitude
     *
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * Set Geo Latitude
     *
     * @param float $latitude
     * @return \Qoliber\Psl\Api\Data\OpenSearchQueryInterface
     */
    public function setLatitude(float $latitude): OpenSearchQueryInterface
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get Geo Longitude
     *
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * Set Geo Longitude
     *
     * @param float $longitude
     * @return \Qoliber\Psl\Api\Data\OpenSearchQueryInterface
     */
    public function setLongitude(float $longitude): OpenSearchQueryInterface
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get Geo Distance
     *
     * @return string|null
     */
    public function getDistance(): ?string
    {
        return $this->getDistance;
    }

    /**
     * Set Geo Distance
     *
     * @param string $distance
     * @return \Qoliber\Psl\Api\Data\OpenSearchQueryInterface
     */
    public function setDistance(string $distance): OpenSearchQueryInterface
    {
        $this->getDistance = $distance;

        return $this;
    }
}
