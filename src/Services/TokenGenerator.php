<?php

namespace Pedros80\LANDEphp\Services;

use GuzzleHttp\Client;
use Pedros80\LANDEphp\Contracts\Tokens;
use Pedros80\LANDEphp\Exceptions\CouldNotGenerateToken;
use Pedros80\LANDEphp\Exceptions\InvalidTokenResponse;
use stdClass;
use Throwable;

final class TokenGenerator implements Tokens
{
    public function __construct(
        private Client $client
    ) {
    }

    public function getToken(): array
    {
        try {
            $response = $this->client->post('');

            /** @var stdClass $body */
            $body = json_decode((string) $response->getBody(), false);

            if (!is_object($body)) {
                throw InvalidTokenResponse::new();
            }

            return $this->parseToken($body);
        } catch (Throwable $e) {
            throw new CouldNotGenerateToken($e->getMessage());
        }
    }

    /**
     * @return array<string, string>
     */
    private function parseToken(stdClass $body): array
    {
        return [
            'user'    => $body->user_id,
            'expires' => date('Y-m-d H:i:s', (int) strtotime("+ {$body->expires_in} seconds")),
            'token'   => $body->access_token,
        ];
    }
}
