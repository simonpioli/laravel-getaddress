<?php
namespace Szhorvath\GetAddress\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * GetAddress Laravel Facade
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
