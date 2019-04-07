<?php

namespace Szhorvath\GetAddress;

/**
 * An individual expanded address returned from the getaddress.io API
 */
class ExpandedAddress
{
    /** @var string */
    protected $buildingNumber;

    /** @var string */
    protected $buildingName;

    /** @var string */
    protected $subBuildingNumber;

    /** @var string */
    protected $subBuildingName;

    /** @var string */
    protected $line1;

    /** @var string */
    protected $line2;

    /** @var string */
    protected $line3;

    /** @var string */
    protected $line4;

    /** @var string */
    protected $locality;

    /** @var string */
    protected $townOrCity;

    /** @var string */
    protected $county;

    /** @var string */
    protected $district;

    /** @var string */
    protected $country;

    /** @var array */
    protected $formattedAddress = [];

    /**
     * ExpandedAddress constructor.
     * @param string $buildingNumber
     * @param string $buildingName
     * @param string $subBuildingNumber
     * @param string $subBuildingName
     * @param string $line1
     * @param string $line2
     * @param string $line3
     * @param string $line4
     * @param string $locality
     * @param string $townOrCity
     * @param string $county
     * @param string $district
     * @param string $country
     * @param array $formattedAddress
     */
    public function __construct(
        $buildingNumber = '',
        $buildingName = '',
        $subBuildingNumber = '',
        $subBuildingName = '',
        $line1 = '',
        $line2 = '',
        $line3 = '',
        $line4 = '',
        $locality = '',
        $townOrCity = '',
        $county = '',
        $district = '',
        $country = '',
        $formattedAddress = []
    ) {
        $this->buildingNumber = $buildingNumber;
        $this->buildingName = $buildingName;
        $this->subBuildingNumber = $subBuildingNumber;
        $this->subBuildingName = $subBuildingName;
        $this->line1 = $line1;
        $this->line2 = $line2;
        $this->line3 = $line3;
        $this->line4 = $line4;
        $this->locality = $locality;
        $this->townOrCity = $townOrCity;
        $this->county = $county;
        $this->district = $district;
        $this->country = $country;
        $this->formattedAddress = $formattedAddress;
    }

    /**
     * @return string
     */
    public function getBuildingNumber()
    {
        return $this->buildingNumber;
    }

    /**
     * @return string
     */
    public function getBuildingName()
    {
        return $this->buildingName;
    }

    /**
     * @return string
     */
    public function getSubBuildingNumber()
    {
        return $this->subBuildingNumber;
    }

    /**
     * @return string
     */
    public function getSubBuildingName()
    {
        return $this->subBuildingName;
    }

    /**
     * @return string
     */
    public function getLine1()
    {
        return $this->line1;
    }

    /**
     * @return string
     */
    public function getLine2()
    {
        return $this->line2;
    }

    /**
     * @return string
     */
    public function getLine3()
    {
        return $this->line3;
    }

    /**
     * @return string
     */
    public function getLine4()
    {
        return $this->line4;
    }

    /**
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @return string
     */
    public function getTownOrCity()
    {
        return $this->townOrCity;
    }

    /**
     * Gets the Locality if it's set, otherwise gets the City
     * Assumes that if there's a locality it's within a city rather than a town
     *
     * @return string
     */
    public function getNormalisedTown()
    {
        if (!empty($this->locality)) {
            return $this->locality;
        }

        return $this->townOrCity;
    }

    /**
     * @return string
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Gets the City if the County line is empty
     *
     * @return string
     */
    public function getNormalisedCounty()
    {
        if (!empty($this->county)) {
            return $this->county;
        }

        return $this->townOrCity;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return array
     */
    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    /**
     * @return string
     */
    public function getFormattedAddressString()
    {
        return implode(', ', $this->formattedAddress);
    }
}
