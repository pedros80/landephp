<?php

namespace Pedros80\LANDEphp\Services;

use GuzzleHttp\Client;
use Pedros80\LANDEphp\Exceptions\CouldNotGenerateToken;
use stdClass;
use Throwable;

final class TokenGenerator
{
    public function __construct(
        private Client $client
    ) {
    }

    public function getToken(): array
    {
        try {
            $response = $this->client->post('');

            $data = json_decode((string) $response->getBody());

            return $this->parseToken($data);
        } catch (Throwable $e) {
            throw new CouldNotGenerateToken($e->getMessage());
        }
    }

    private function parseToken(stdClass $data): array
    {
        return [
            'user'    => $data->user_id,
            'expires' => date('Y-m-d H:i:s', (int) strtotime("+ {$data->expires_in} seconds")),
            'token'   => $data->access_token,
        ];
    }
}
