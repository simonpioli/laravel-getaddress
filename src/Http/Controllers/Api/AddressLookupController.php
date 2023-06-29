<?php

namespace Szhorvath\GetAddress\Http\Controllers\Api;

use Illuminate\Http\JsonResponse as Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Szhorvath\GetAddress\Facades\GetAddress;
use Szhorvath\GetAddress\GetAddressAuthenticationFailedException;
use Szhorvath\GetAddress\GetAddressRequestException;
use Szhorvath\GetAddress\Http\Middleware\LocalOnly;

class AddressLookupController extends BaseController
{
    const POSTCODE_CACHE_KEY = 'postcode_';
    const ADDRESS_CACHE_KEY = 'address_';

    public function __construct()
    {
        if (config('getaddress.protect')) {
            $this->middleware(LocalOnly::class);
        }
    }

    /**
     * Postcode lookup
     */
    public function lookup(string $postcode): Response
    {
        $now = now();
        $response = [
            'addresses' => [],
            'errorMessage' => '',
            'code' => null
        ];

        $postcodeForKey = str_replace(' ', '', trim(strtolower($postcode)));

        if (Cache::has(self::POSTCODE_CACHE_KEY . $postcodeForKey)) {
            $response = Cache::get(self::POSTCODE_CACHE_KEY . $postcodeForKey);
            return new Response($response, $response['code']);
        }

        try {
            $responseCode = 200;
            $response['addresses'] = GetAddress::list($postcodeForKey);
            $response['code'] = $responseCode;
            Cache::add(
                self::POSTCODE_CACHE_KEY . $postcodeForKey,
                $response,
                now()->addDays(7)
            );
        } catch (GetAddressRequestException $e) {
            $responseCode = 400;
            $response['errorMessage'] = $e->getMessage();
            $response['code'] = $responseCode;
            Cache::add(
                self::POSTCODE_CACHE_KEY . $postcodeForKey,
                $response,
                now()->addMinutes(10)
            );
        } catch (GetAddressAuthenticationFailedException $e) {
            $responseCode = 412;
            $response['errorMessage'] = 'The address lookup system is not currently working. Please enter your address manually.';
            $response['code'] = $responseCode;
            Cache::add(
                self::POSTCODE_CACHE_KEY . $postcodeForKey,
                $response,
                now()->addMinutes(10)
            );
        }

        return new Response($response, $responseCode);
    }

    public function fetch(string $id): Response
    {
        if (Cache::has(self::ADDRESS_CACHE_KEY . $id)) {
            $response = Cache::get(self::ADDRESS_CACHE_KEY . $id);
            return new Response($response, $response['code']);
        }

        try {
            $responseCode = 200;
            $rawAddress = GetAddress::fetch($id);
            $flat_no = !empty($rawAddress->getSubBuildingName()) ? $rawAddress->getSubBuildingName(
            ) : $rawAddress->getSubBuildingNumber();
            $building_no_name = !empty($rawAddress->getBuildingName()) ? $rawAddress->getBuildingName(
            ) : $rawAddress->getBuildingNumber();
            $address_line_1 = '';

            if (
                !empty($rawAddress->getBuildingName()) &&
                !empty($rawAddress->getBuildingNumber())
            ) {
                $address_line_1 = $rawAddress->getBuildingNumber() . ' ';
            }
            $address_line_1 .= preg_match(
                '/Flat [0-9]+[A-Za-z]?/',
                $rawAddress->getLine1()
            ) ? $rawAddress->getLine2() : $rawAddress->getLine1();
            $address_line_2 = preg_match('/Flat [0-9]+[A-Za-z]?/', $rawAddress->getLine1()) ? $rawAddress->getLine3(
            ) : $rawAddress->getLine2();

            if ($address_line_2 == $address_line_1) {
                $address_line_2 = '';
            }

            $address = [
                'flat_no' => $flat_no,
                'building_no_name' => $building_no_name,
                'address_line_1' => $address_line_1,
                'address_line_2' => $address_line_2,
                'town' => $rawAddress->getNormalisedTown(),
                'county' => $rawAddress->getNormalisedCounty() != $rawAddress->getNormalisedTown(
                ) ? $rawAddress->getNormalisedCounty() : '',
                'postcode' => $rawAddress->getPostcode(),
                'formatted_address' => $rawAddress->getFormattedAddress()
            ];

            $response['address'] = $address;
            $response['code'] = $responseCode;
            Cache::add(
                self::ADDRESS_CACHE_KEY . $id,
                $response,
                now()->addDays(7)
            );
        } catch (GetAddressRequestException $e) {
            $responseCode = 400;
            $response['errorMessage'] = $e->getMessage();
            $response['code'] = $responseCode;
            Cache::add(
                self::ADDRESS_CACHE_KEY . $id,
                $response,
                now()->addMinutes(10)
            );
        } catch (GetAddressAuthenticationFailedException $e) {
            $responseCode = 412;
            $response['errorMessage'] = 'The address lookup system is not currently working. Please enter your address manually.';
            $response['code'] = $responseCode;
            Cache::add(
                self::ADDRESS_CACHE_KEY . $id,
                $response,
                now()->addMinutes(10)
            );
        }

        return new Response($response, $responseCode);
    }
}
