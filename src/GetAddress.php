<?php

namespace Szhorvath\GetAddress;

use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class GetAddress
{
    /**
     * Instantiate GetAddress
     */
    public function __construct()
    {
        Http::macro('getaddress', function () {
            return Http::baseUrl(config('getaddress.base_url'))
                ->withOptions([
                    'query' => [
                        'api-key' => config('getaddress.api_key'),
                    ],
                ]);
        });
    }

    /**
     * @throws GetAddressAuthenticationFailedException
     * @throws GetAddressRequestException
     */
    public function list(string $postcode, array $options = []): array
    {
        $defaults = [
            'all' => true,
        ];
        $mergedOptions = array_merge($defaults, $options);

        try {
            $response = Http::getaddress()->post(sprintf('autocomplete/%s', $postcode), $mergedOptions);
            $response->throw();
        } catch (RequestException $e) {
            if ($e->response->status() == 401) {
                throw new GetAddressAuthenticationFailedException();
            }
            throw new GetAddressRequestException();
        } catch (Exception $e) {
            throw new GetAddressRequestException($e->getMessage(), $e->getCode());
        }

        return $response->json()['suggestions'];
    }

    public function fetch(string $id): Address
    {
        try {
            $response = Http::getaddress()->get(sprintf('get/%s', $id));
            $response->throw();
        } catch (RequestException $e) {
            if ($e->response->status() == 401) {
                throw new GetAddressAuthenticationFailedException();
            }
            throw new GetAddressRequestException();
        } catch (Exception $e) {
            throw new GetAddressRequestException($e->getMessage(), $e->getCode());
        }

        return $this->hydrateAddress($response->json());
    }

    private function hydrateAddress(array $addressArray): Address
    {
        return new Address(
            $addressArray['building_number'],
            $addressArray['building_name'],
            $addressArray['sub_building_number'],
            $addressArray['sub_building_name'],
            $addressArray['line_1'],
            $addressArray['line_2'],
            $addressArray['line_3'],
            $addressArray['line_4'],
            $addressArray['locality'],
            $addressArray['town_or_city'],
            $addressArray['county'],
            $addressArray['district'],
            $addressArray['postcode'],
            $addressArray['country'],
            $addressArray['formatted_address']
        );
    }
}
