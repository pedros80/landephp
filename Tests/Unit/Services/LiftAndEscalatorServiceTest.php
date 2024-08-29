<?php

namespace Tests\Unit\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Pedros80\LANDEphp\Exceptions\InvalidServiceResponse;
use Pedros80\LANDEphp\Services\LiftAndEscalatorService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class LiftAndEscalatorServiceTest extends TestCase
{
    use ProphecyTrait;

    public function testServiceGetReturnsInvalidJsonThrowsException(): void
    {
        $this->expectException(InvalidServiceResponse::class);
        $this->expectExceptionMessage('Invalid Service Response - could not decode to object');

        $client = $this->prophesize(Client::class);

        $client->get(
            'api/v2/sensors?num=50&offset=0',
            [
                RequestOptions::HEADERS => [
                    'Authorisation' => 'Bearer token'
                ]
            ])->shouldBeCalled()->willReturn(new Response(200, [], 'invalid-json'));

        $service = new LiftAndEscalatorService($client->reveal());

        $service->getSensors('token');
    }

    public function testGetSensorsHitsCorrectEndpointWithNoPagination(): void
    {
        $client = $this->prophesize(Client::class);

        $client->get(
            'api/v2/sensors?num=50&offset=0',
            [
                RequestOptions::HEADERS => [
                    'Authorisation' => 'Bearer token'
                ]
            ])->shouldBeCalled()->willReturn(new Response(200, [], '{}'));

        $service = new LiftAndEscalatorService($client->reveal());

        $service->getSensors('token');
    }

    public function testGetSensorsHitsCorrectEndpointWithPagination(): void
    {
        $client = $this->prophesize(Client::class);

        $client->get(
            'api/v2/sensors?num=500&offset=100',
            [
                RequestOptions::HEADERS => [
                    'Authorisation' => 'Bearer token'
                ]
            ])->shouldBeCalled()->willReturn(new Response(200, [], '{}'));

        $service = new LiftAndEscalatorService($client->reveal());

        $service->getSensors('token', 500, 100);
    }

    public function testGetSensorInfoByIdHitsCorrectEndpoint(): void
    {
        $client = $this->prophesize(Client::class);

        $client->get(
            'api/v2/sensors/1234',
            [
                RequestOptions::HEADERS => [
                    'Authorisation' => 'Bearer token'
                ]
            ])->shouldBeCalled()->willReturn(new Response(200, [], '{}'));

        $service = new LiftAndEscalatorService($client->reveal());

        $service->getSensorInfoById(1234, 'token');
    }

    public function testGetAssetInfoByIdHitsCorrectEndpoint(): void
    {
        $client = $this->prophesize(Client::class);

        $query = <<<GQL
            query AssetInfoById {
                assets(where: {id: {_eq: "1234"}}) {
                    blockId
                    description
                    crs
                    type
                    location
                    id
                    displayName
                    sensorId
                    prn
                    status {
                        status
                        sensorId
                        isolated
                        engineerOnSite
                        independent
                    }
                }
            }
        GQL;

        $client->post('graphql/v2',
        [
            RequestOptions::HEADERS => [
                'Authorisation' => 'Bearer token',
            ],
            RequestOptions::JSON => [
                RequestOptions::QUERY => $query,
            ],
        ])->shouldBeCalled()->willReturn(new Response(200, [], '{}'));

        $service = new LiftAndEscalatorService($client->reveal());

        $service->getAssetInfoById(1234, 'token');
    }

    public function testGetAssetsByStationCodeHitsCorrectEndpoint(): void
    {
        $client = $this->prophesize(Client::class);

        $query = <<<GQL
            query AssetsByStationCode {
                assets(where: {crs: {_eq: "KDY"}}) {
                    blockId
                    description
                    crs
                    type
                    location
                    id
                    displayName
                    sensorId
                    prn
                    status {
                        status
                        sensorId
                        isolated
                        engineerOnSite
                        independent
                    }
                }
            }
        GQL;

        $client->post('graphql/v2',
        [
            RequestOptions::HEADERS => [
                'Authorisation' => 'Bearer token',
            ],
            RequestOptions::JSON => [
                RequestOptions::QUERY => $query,
            ],
        ])->shouldBeCalled()->willReturn(new Response(200, [], '{}'));

        $service = new LiftAndEscalatorService($client->reveal());

        $service->getAssetsByStationCode('KDY', 'token');
    }

    public function testServicePostReturnsInvalidJsonThrowsException(): void
    {
        $this->expectException(InvalidServiceResponse::class);
        $this->expectExceptionMessage('Invalid Service Response - could not decode to object');

        $client = $this->prophesize(Client::class);

        $query = <<<GQL
            query AssetsByStationCode {
                assets(where: {crs: {_eq: "KDY"}}) {
                    blockId
                    description
                    crs
                    type
                    location
                    id
                    displayName
                    sensorId
                    prn
                    status {
                        status
                        sensorId
                        isolated
                        engineerOnSite
                        independent
                    }
                }
            }
        GQL;

        $client->post('graphql/v2',
        [
            RequestOptions::HEADERS => [
                'Authorisation' => 'Bearer token',
            ],
            RequestOptions::JSON => [
                RequestOptions::QUERY => $query,
            ],
        ])->shouldBeCalled()->willReturn(new Response(200, [], 'invalid-json'));

        $service = new LiftAndEscalatorService($client->reveal());

        $service->getAssetsByStationCode('KDY', 'token');
    }
}
