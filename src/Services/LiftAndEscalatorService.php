<?php

namespace Pedros80\LANDEphp\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Pedros80\LANDEphp\Contracts\LiftsAndEscalators;
use Pedros80\LANDEphp\Exceptions\InvalidServiceResponse;
use stdClass;

final class LiftAndEscalatorService implements LiftsAndEscalators
{
    public function __construct(
        private Client $client
    ) {
    }

    public function getAssetsByStationCode(string $station, string $token): stdClass
    {
        $query = <<<GQL
            query AssetsByStationCode {
                assets(where: {crs: {_eq: "%s"}}) {
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

        $query = sprintf($query, $station);

        return $this->post($query, $token);
    }

    public function getAssetInfoById(int $id, string $token): stdClass
    {
        $query = <<<GQL
            query AssetInfoById {
                assets(where: {id: {_eq: "%d"}}) {
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

        $query = sprintf($query, $id);

        return $this->post($query, $token);
    }

    public function getSensors(string $token, int $num=50, int $offset=0): stdClass
    {
        return $this->get("api/v2/sensors?num={$num}&offset={$offset}", $token);
    }

    public function getSensorInfoById(int $id, string $token): stdClass
    {
        return $this->get("api/v2/sensors/{$id}", $token);
    }

    private function get(string $url, string $token): stdClass
    {
        $response = $this->client->get($url, [
            RequestOptions::HEADERS => [
                'Authorisation' => "Bearer {$token}",
            ],
        ]);

        /** @var stdClass $data */
        $data = json_decode($response->getBody(), false);

        if (!is_object($data)) {
            throw InvalidServiceResponse::new();
        }

        return $data;
    }

    private function post(string $query, string $token): stdClass
    {
        $response = $this->client->post('graphql/v2', [
            RequestOptions::HEADERS => [
                'Authorisation' => "Bearer {$token}",
            ],
            RequestOptions::JSON => [
                RequestOptions::QUERY => $query,
            ],
        ]);

        /** @var stdClass $data */
        $data = json_decode($response->getBody(), false);

        if (!is_object($data)) {
            throw InvalidServiceResponse::new();
        }

        return $data;
    }
}
