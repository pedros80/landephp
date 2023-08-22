<?php

namespace Tests\Unit\Services;

use Exception;
use GuzzleHttp\Client;
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
}
