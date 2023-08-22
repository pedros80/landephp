<?php

namespace Tests\Unit\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Pedros80\LANDEphp\Exceptions\CouldNotGenerateToken;
use Pedros80\LANDEphp\Services\TokenGenerator;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class TokenGeneratorTest extends TestCase
{
    use ProphecyTrait;

    public function testErrorGeneratingTokenThrowsException(): void
    {
        $this->expectException(CouldNotGenerateToken::class);
        $this->expectExceptionMessage('Something Wrong...');

        $client = $this->prophesize(Client::class);

        $client->post('')->shouldBeCalled()->willThrow(new Exception('Something Wrong...'));

        $service = new TokenGenerator(
            $client->reveal()
        );

        $service->getToken();
    }

    public function testCanGetTokenAndParseIt(): void
    {
        $client = $this->prophesize(Client::class);

        $client->post('')->shouldBeCalled()->willReturn(new Response(200, [], '{"user_id":"user_id","expires_in":8900,"access_token":"access_token"}'));

        $service = new TokenGenerator(
            $client->reveal()
        );

        $token = $service->getToken();

        $this->assertIsArray($token);
        $this->assertEquals('user_id', $token['user']);
        $this->assertEquals('access_token', $token['token']);
    }
}
