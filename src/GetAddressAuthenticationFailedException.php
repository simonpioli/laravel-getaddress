<?php

namespace Szhorvath\GetAddress;

use Exception;

class GetAddressAuthenticationFailedException extends Exception
{
    protected $message = 'GetAddress.io authentication failed. Please check your API key';
}
