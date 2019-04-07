<?php

namespace Szhorvath\GetAddress;

class GetAddressResponse
{
    /**
     * Address longitude
     *
     * @var string
     */
    protected $longitude;

    /**
     * Address latitude
     *
     * @var string
     */
    protected $latitude;

    /**
     * Addresses array
     *
     * @var array
     */
    protected $addresses;

    /**
     * Set the longitude
     *
     * @param string $longitude
     * @return void
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set address lattitude
     *
     * @param string $latitude
     * @return void
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get address lattitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get addresses array
     *
     * @return array
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Get address
     *
     * @return Address
     */
    public function getAddress()
    {
        return (is_array($this->addresses) && count($this->addresses) === 1)? $this->addresses[0]: null;
    }

    /**
     * Set an address to the addresses array
     *
     * @param Address $address
     */
    public function addAddress($address)
    {
        $this->addresses[] = $address;
    }
}
