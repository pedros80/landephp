<?php

namespace Tests\Unit\Factories;

use Pedros80\LANDEphp\Factories\ServicesFactory;
use Pedros80\LANDEphp\Services\LiftAndEscalatorService;
use Pedros80\LANDEphp\Services\TokenGenerator;
use PHPUnit\Framework\TestCase;

final class ServicesFactoryTest extends TestCase
{
    public function testCanCreateTokenGenerator(): void
    {
        $factory = new ServicesFactory();
        $service = $factory->makeTokenGenerator('api_key');

        $this->assertInstanceOf(TokenGenerator::class, $service);
    }

    public function testCanCreateAService(): void
    {
        $factory = new ServicesFactory();
        $service = $factory->makeLiftAndEscalatorService('api_key');

        $this->assertInstanceOf(LiftAndEscalatorService::class, $service);
    }
}
