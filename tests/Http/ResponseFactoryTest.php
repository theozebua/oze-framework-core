<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use OzeFramework\Http\Enums\StatusCode;
use Psr\Http\Message\ResponseInterface;

final class ResponseFactoryTest extends AbstractMessage
{
    public function testResponseFactoryReturnsResponseObjectThatImplementsPsrHttpMessageResponseInterface(): void
    {
        $response = $this->responseFactory->createResponse();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCode::OK->value, $response->getStatusCode());
        $this->assertSame(StatusCode::OK->getReasonPhrase(), $response->getReasonPhrase());
    }
}
