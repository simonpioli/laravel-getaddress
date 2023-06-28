<?php

namespace Szhorvath\GetAddress;

use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class GetAddress
{
    /**
     * Instantiate GetAddress
     *
     * @param string $apiKey
     */
    public function __construct(protected string $apiKey)
    {
        Http::macro('getaddress', function () {
            return Http::baseUrl(config('getaddress.base_url'));
        });
    }

    /**
     * @throws GetAddressAuthenticationFailedException
     * @throws GetAddressRequestException
     */
    public function list(string $postcode, ?array $options = []): array
    {
        $defaults = [
            'all' => true,
        ];
        $mergedOptions = array_merge($defaults, $options);

        $requestParameters = array_merge(['api-key' => $this->apiKey], $mergedOptions);

        try {
            $response = Http::getaddress()->get(sprintf('autocomplete/%s', $postcode), $requestParameters);
            $response->throw();
        } catch (RequestException $e) {
            if ($e->response->status() == 401) {
                throw new GetAddressAuthenticationFailedException();
            }
            throw new GetAddressRequestException();
        } catch (Exception $e) {
            throw new GetAddressRequestException($e->getMessage(), $e->getCode());
        }

        return $response->json();
    }

    public function fetch(string $id): GetAddressResponse | GetAddressExpandedResponse
    {
        try {
            $response = Http::getaddress()->get(sprintf('get/%s', $id), ['api-key' => $this->apiKey]);
        } catch (RequestException $e) {
            if ($e->response->status() == 401) {
                throw new GetAddressAuthenticationFailedException();
            }
            throw new GetAddressRequestException();
        } catch (Exception $e) {
            throw new GetAddressRequestException($e->getMessage(), $e->getCode());
        }

        return $this->parseResponse($response->json());
    }

    /**
     * Processes the response coming from getaddress api
     */
    private function parseResponse(array $addressArray, ?array $options = []): GetAddressExpandedResponse|GetAddressResponse
    {

        if (array_key_exists('expand', $options) && $options['expand'] === 'true') {
            $getAddressResponse = $this->hydrateExpandedAddresses($addressArray);
        } else {
            $getAddressResponse = $this->hydrateAddresses($addressArray);
        }

        return $getAddressResponse;
    }

    private function hydrateAddresses(array $addressArray): GetAddressResponse
    {
        $getAddressResponse = new GetAddressResponse();

        //Set the longitude and latitude fields
        $getAddressResponse
            ->setLongitude($addressArray['longitude'])
            ->setLatitude($addressArray['latitude']);

        //Set the address fields
        foreach ($addressArray['addresses'] as $addressLine) {
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

    private function hydrateExpandedAddresses(array $addressArray): GetAddressExpandedResponse
    {
        $getAddressResponse = new GetAddressExpandedResponse();

        //Set the longitude and latitude fields
        $getAddressResponse
            ->setLongitude($addressArray['longitude'])
            ->setLatitude($addressArray['latitude']);

        //Set the address fields
        foreach ($addressArray['addresses'] as $addressLine) {
            $getAddressResponse->addAddress(
                new ExpandedAddress(
                    trim($addressLine['building_number']),
                    trim($addressLine['building_name']),
                    trim($addressLine['sub_building_number']),
                    trim($addressLine['sub_building_name']),
                    trim($addressLine['thoroughfare']),
                    trim($addressLine['line_2']),
                    trim($addressLine['line_3']),
                    trim($addressLine['line_4']),
                    trim($addressLine['locality']),
                    trim($addressLine['town_or_city']),
                    trim($addressLine['county']),
                    trim($addressLine['district']),
                    trim($addressLine['country']),
                    $addressLine['formatted_address']
                )
            );
        }

        return $getAddressResponse;
    }
}
