<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use OzeFramework\Http\Enums\StatusCode;

class ResponseTest extends AbstractMessage
{
    public function testResponseReturnsStatusCode(): void
    {
        $response = $this->responseFactory->createResponse();

        $this->assertSame(StatusCode::OK->value, $response->getStatusCode());
    }

    public function testResponseReturnsReasonPhrase(): void
    {
        $response = $this->responseFactory->createResponse();

        $this->assertSame(StatusCode::OK->getReasonPhrase(), $response->getReasonPhrase());
    }

    public function testResponseReturnNewObjectWithModifiedStatus(): void
    {
        $response = $this->responseFactory->createResponse();

        $newResponse = $response->withStatus(StatusCode::CREATED->value, StatusCode::CREATED->getReasonPhrase());

        $this->assertSame(StatusCode::CREATED->value, $newResponse->getStatusCode());
        $this->assertSame(StatusCode::CREATED->getReasonPhrase(), $newResponse->getReasonPhrase());
        $this->assertNotSame($response, $newResponse);
    }
}
