<?php

namespace Pedros80\LANDEphp\Contracts;

interface Tokens
{
    /**
     * @return array<string, string>
     */
    public function getToken(): array;
}
