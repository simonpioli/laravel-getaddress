<?php

namespace Szhorvath\GetAddress;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Szhorvath\GetAddress\GetAddressResponse;
use Szhorvath\GetAddress\Address;

class GetAddress
{
    /**
     * Guzzle client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Getaddress api key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Instantiate GetAddress
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->client = new Client([
            'base_uri' => config('getaddress.base_url')
        ]);

        $this->apiKey = $apiKey;
    }

    /**
     * Retrieves the addresses from get address api
     *
     * @param $postcode
     * @param string $houseNumOrName
     * @param array $options
     * @return GetAddressResponse\GetAddressResponse
     * @throws GetAddressAuthenticationFailedException
     * @throws GetAddressRequestException
     */
    public function lookup($postcode, $houseNumOrName = '', $options = [])
    {
        $requestParameters = ['auth' => ['api-key', $this->apiKey]];
        if (!empty($options)) {
            $requestParameters['query'] = $options;
        }

        try {
            $response = $this->client->get(sprintf('%s/%s', $postcode, $houseNumOrName), $requestParameters);
        } catch (RequestException $e) {
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() == 401) {
                throw new GetAddressAuthenticationFailedException();
            }

            throw new GetAddressRequestException();
        }

        $parsedResponse = $this->parseResponse((string) $response->getBody());

        return  $parsedResponse;
    }

    /**
     * Processes the response coming from getaddress api
     *
     * @param string $response
     * @return \Szhorvath\GetAddress\GetAddressResponse\GetAddressResponse
     */
    public function parseResponse($response)
    {
        //Convert the response from JSON into an object
        $responseObj = json_decode($response);

        $getAddressResponse = new GetAddressResponse();

        //Set the longitude and latitude fields
        $getAddressResponse->setLongitude($responseObj->Longitude);
        $getAddressResponse->setLatitude($responseObj->Latitude);

        //Set the address fields
        foreach ($responseObj->Addresses as $addressLine) {
            $addressParts = explode(',', $addressLine);
            $getAddressResponse->addAddress(
                new Address(
                    trim($addressParts[0]), //addr1
                    trim($addressParts[1]), //addr2
                    trim($addressParts[2]), //addr3
                    trim($addressParts[3]), //addr4
                    trim($addressParts[4]), //town
                    trim($addressParts[5]), //postal town
                    trim($addressParts[6]) //county
                )
            );
        }

        return $getAddressResponse;
    }
}
