<?php

namespace Szhorvath\GetAddress;

/**
 * An individual expanded address returned from the getaddress.io API
 */
class Address
{
    public function __construct(
        protected ?string $buildingNumber,
        protected ?string $buildingName,
        protected ?string $subBuildingNumber,
        protected ?string $subBuildingName,
        protected ?string $line1,
        protected ?string $line2,
        protected ?string $line3,
        protected ?string $line4,
        protected ?string $locality,
        protected ?string $townOrCity,
        protected ?string $county,
        protected ?string $district,
        protected ?string $postcode,
        protected ?string $country,
        protected ?array $formattedAddress
    ) {
        //
    }

    public function getBuildingNumber(): ?string
    {
        return $this->buildingNumber;
    }

    public function getBuildingName(): ?string
    {
        return $this->buildingName;
    }

    public function getSubBuildingNumber(): ?string
    {
        return $this->subBuildingNumber;
    }

    public function getSubBuildingName(): ?string
    {
        return $this->subBuildingName;
    }

    public function getLine1(): ?string
    {
        return $this->line1;
    }

    public function getLine2(): ?string
    {
        return $this->line2;
    }

    public function getLine3(): ?string
    {
        return $this->line3;
    }

    public function getLine4(): ?string
    {
        return $this->line4;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function getTownOrCity(): ?string
    {
        return $this->townOrCity;
    }

    /**
     * Gets the Locality if it's set, otherwise gets the City
     * Assumes that if there's a locality it's within a city rather than a town
     */
    public function getNormalisedTown(): ?string
    {
        if (!empty($this->locality)) {
            return $this->locality;
        }

        return $this->townOrCity;
    }

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    /**
     * Gets the City if the County line is empty
     */
    public function getNormalisedCounty(): ?string
    {
        if (!empty($this->county)) {
            return $this->county;
        }

        return $this->townOrCity;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getFormattedAddress(): ?array
    {
        return $this->formattedAddress;
    }
}
