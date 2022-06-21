<?php

namespace Szhorvath\GetAddress;

/**
 * An individual address returned from the getaddress.io API
 */
class Address
{
    /**
     * Instantiates a new Address object
     */
    public function __construct(
        protected string $line1,
        protected ?string $line2,
        protected ?string $line3,
        protected ?string $line4,
        protected ?string $town,
        protected ?string $postalTown,
        protected ?string $county
    ) {
        //
    }

    public function getLine1(): string
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

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function getPostalTown(): ?string
    {
        return $this->postalTown;
    }


    /**
     * Returns the most appropriate of the two town fields
     */
    public function getNormalisedTown(): string
    {
        if ($this->town != '') {
            return $this->town;
        }

        return $this->postalTown;
    }

    public function getCounty(): ?string
    {
        return $this->county;
    }

    /**
     * Returns the address as comma separated string
     */
    public function toCsv(): string
    {
        return sprintf('%s,%s,%s,%s,%s,%s,%s', $this->getLine1(), $this->getLine2(), $this->getLine3(), $this->getLine4(), $this->getTown(), $this->getPostalTown(), $this->getCounty());
    }
}
