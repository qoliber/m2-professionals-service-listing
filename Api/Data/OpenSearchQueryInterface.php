<?php

declare(strict_types=1);

namespace Qoliber\Psl\Api\Data;

interface OpenSearchQueryInterface
{
    /**
     * Get country
     *
     * @return string|null
     */
    public function getCountry(): ?string;

    /**
     * Set country
     *
     * @param string $country
     * @return self
     */
    public function setCountry(string $country): self;

    /**
     * Get city
     *
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * Set city
     *
     * @param string $city
     * @return self
     */
    public function setCity(string $city): self;

    /**
     * Get services
     *
     * @return string[]
     */
    public function getServices(): array;

    /**
     * Set services
     *
     * @param string[] $services
     * @return self
     */
    public function setServices(array $services): self;

    /**
     * Get certificates
     *
     * @return string[]
     */
    public function getCertificates(): array;

    /**
     * Set certificates
     *
     * @param string[] $certificates
     * @return self
     */
    public function setCertificates(array $certificates): self;

    /**
     * Get Geo Latitude
     *
     * @return float|null
     */
    public function getLatitude(): ?float;

    /**
     * Set Geo Latitude
     *
     * @param float $latitude
     * @return self
     */
    public function setLatitude(float $latitude): self;

    /**
     * Get Geo Longitude
     *
     * @return float|null
     */
    public function getLongitude(): ?float;

    /**
     * Set Geo Longitude
     *
     * @param float $longitude
     * @return self
     */
    public function setLongitude(float $longitude): self;

    /**
     * Get Get Distance
     *
     * @return string|null
     */
    public function getDistance(): ?string;

    /**
     * Set Get Distance
     *
     * @param string $distance
     * @return self
     */
    public function setDistance(string $distance): self;
}
