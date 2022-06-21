<?php

namespace Szhorvath\GetAddress;

class GetAddressResponse
{
    protected string $longitude;
    protected string $latitude;
    protected array $addresses;

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function getAddresses(): array
    {
        return $this->addresses;
    }

    public function getAddress(): ?Address
    {
        return (count($this->addresses) === 1) ? $this->addresses[0] : null;
    }

    public function addAddress(Address $address): self
    {
        $this->addresses[] = $address;
        return $this;
    }
}
