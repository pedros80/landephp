<?php

namespace Pedros80\LANDEphp\Factories;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Pedros80\LANDEphp\Services\LiftAndEscalatorService;
use Pedros80\LANDEphp\Services\TokenGenerator;

final class ServicesFactory
{
    private const BASE_URI      = 'https://nr-lift-and-escalator.azure-api.net/';
    private const AUTH_ENDPOINT = 'auth/token/';
    private const USER_AGENT    = 'LANDEphp';
    private const TIMEOUT       = 20;

    public function makeTokenGenerator(string $apiKey): TokenGenerator
    {
        return new TokenGenerator(
            new Client([
                'base_uri'               => self::BASE_URI . self::AUTH_ENDPOINT,
                RequestOptions::HEADERS  => [
                    'User-Agent'    => self::USER_AGENT,
                    'Cache-Control' => 'no-cache',
                    'x-lne-api-key' => $apiKey,
                ],
                RequestOptions::TIMEOUT => self::TIMEOUT,
            ]),
        );
    }

    public function makeLiftAndEscalatorService(string $apiKey): LiftAndEscalatorService
    {
        return new LiftAndEscalatorService(
            new Client([
                'base_uri'               => self::BASE_URI,
                RequestOptions::HEADERS  => [
                    'User-Agent'    => self::USER_AGENT,
                    'Content-Type'  => 'application/json',
                    'x-lne-api-key' => $apiKey,
                ],
                RequestOptions::TIMEOUT => self::TIMEOUT,
            ])
        );
    }
}
