<?php
namespace Szhorvath\GetAddress\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Szhorvath\GetAddress\GetAddress list(string $postcode, array|null $options)
 * @method static \Szhorvath\GetAddress\GetAddress fetch(string $id)
 */
class GetAddress extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'getaddress';
    }
}
