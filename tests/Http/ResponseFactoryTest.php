<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use OzeFramework\Http\Factory\Response;
use Psr\Http\Message\ResponseInterface;

final class ResponseFactoryTest extends AbstractUri
{
    public function testResponseFactoryReturnsUriObjectThatImplementsPsrHttpMessageResponseInterface(): void
    {
        $response = Response::create();

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
