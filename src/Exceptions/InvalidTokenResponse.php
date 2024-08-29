<?php

namespace Pedros80\LANDEphp\Exceptions;

use Exception;

final class InvalidTokenResponse extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message, 400);
    }

    public static function new(): InvalidTokenResponse
    {
        return new InvalidTokenResponse('Invalid Token Response - could not decode to object');
    }
}
