# laravel-getaddress

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Laravel-getaddress provides a wrapper around [getaddress.io](getaddress.io) for laravel 5.4 and up. This package is based on [PeteLawrence/getaddress](https://github.com/PeteLawrence/getaddress) PHP library.

## Installation in Laravel 5.4

You can pull in the package via composer:

```bash
$ composer require szhorvath/laravel-getaddress
```

You need to add Szhorvath\GetAddress\GetAddressServiceProvider to your `config/app.php` providers array:

```php
Szhorvath\GetAddress\GetAddressServiceProvider::class,
```

Add the following environmental variable to your `.env` file

```php
GETADDRESSIO_API_KEY=your-api-key
```

You must publish the config file:

```bash
$ php artisan vendor:publish --provider="Szhorvath\GetAddress\GetAddressServiceProvider"
```

## Installation in Laravel 5.5 and up

You can pull in the package via composer:

```bash
$ composer require szhorvath/laravel-getaddress
```

The package will automatically register itself.

Add the following environmental variable to your `.env` file

```php
GETADDRESSIO_API_KEY=your-api-key
```

You must publish the config file:

```bash
$ php artisan vendor:publish --provider="Szhorvath\GetAddress\GetAddressServiceProvider"
```

## Usage

In a controller you can use the Facade. The GetAddress always returns an array

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Szhorvath\GetAddress\Facades\GetAddress;

class AddressController extends Controller
{
    public function lookup()
    {
        $result = GetAddress::lookup('S20 8JH');

        $longitude = $result->getLongitude();
        $latitude = $result->getLatitude();
        $addressList = $result->getAddresses();

        foreach ($addressList as $key => $address) {
            $line1 = $address->getLine1();
            $line2 = $address->getLine2();
            $line3 = $address->getLine3();
            $line4 = $address->getLine4();
            $town  = $address->getTown();
            $postalTown = $address->getPostalTown();
            $normalisedTown = $address->getNormalisedTown();
            $county = $address->getCounty();
            #Full address as comma separated string
            $csv = $address->toCsv();
        }

        #Or for a specific address you can pass the house number or name as second parameter
        $result = GetAddress::lookup('S20 8JH', 5);
        if ($address = $result->getAddress()) {
            $town  = $address->getTown();
        };
    }
}
```

### Options
As per the [GetAddress documentation](https://getaddress.io/Documentation) you can pass a house number or building name as a 2nd parameter.

The 3rd parameter is an optional array of options.

|Property|Description|Type|
|---|---|---|
|format|Formats the addresses as an arrays.|string (true/false)|
|sort|Numerically sorts the addresses.|string (true/false)|
|expand|Expands addresses in to individual named components, such as building number and thoroughfare.|string (true/false)|

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email z.sandor.horvath@gmail.com instead of using the issue tracker.

## Credits

-   [Sandor Horvath][link-author]
-   [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/szhorvath/laravel-getaddress.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/szhorvath/laravel-getaddress/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/szhorvath/laravel-getaddress.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/szhorvath/laravel-getaddress.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/szhorvath/laravel-getaddress.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/szhorvath/laravel-getaddress
[link-travis]: https://travis-ci.org/szhorvath/laravel-getaddress
[link-scrutinizer]: https://scrutinizer-ci.com/g/szhorvath/laravel-getaddress/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/szhorvath/laravel-getaddress
[link-downloads]: https://packagist.org/packages/szhorvath/laravel-getaddress
[link-author]: https://github.com/szhorvath
[link-contributors]: ../../contributors
