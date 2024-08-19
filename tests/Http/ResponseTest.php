<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use OzeFramework\Http\Enums\StatusCode;
use OzeFramework\Http\Factory\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testResponseReturnsStatusCode(): void
    {
        $response = Response::create();

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testResponseReturnsReasonPhrase(): void
    {
        $response = Response::create();

        $this->assertSame('Ok', $response->getReasonPhrase());
    }

    public function testResponseReturnNewObjectWithModifiedStatus(): void
    {
        $response = Response::create();

        $newResponse = $response->withStatus(StatusCode::CREATED->value, StatusCode::CREATED->getReasonPhrase());

        $this->assertSame(201, $newResponse->getStatusCode());
        $this->assertSame('Created', $newResponse->getReasonPhrase());
        $this->assertNotSame($response, $newResponse);
    }
}
