<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use OzeFramework\Http\Factory\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

final class ResponseFactoryTest extends TestCase
{
    public function testResponseFactoryReturnsResponseObjectThatImplementsPsrHttpMessageResponseInterface(): void
    {
        $response = Response::create();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Ok', $response->getReasonPhrase());
    }
}
