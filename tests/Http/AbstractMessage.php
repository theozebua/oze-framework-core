<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use OzeFramework\Http\Factory\Response;
use OzeFramework\Http\Factory\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

abstract class AbstractMessage extends TestCase
{
    protected Response $responseFactory;

    protected Stream $streamFactory;

    protected function setUp(): void
    {
        $this->responseFactory = new Response();
        $this->streamFactory = new Stream();
    }

    public function testMessageReturnsProtocolVersion(): void
    {
        $response = $this->responseFactory->createResponse();
        $protocolVersion = $response->getProtocolVersion();

        $this->assertSame('1.1', $protocolVersion);
    }

    public function testMessageReturnsHeaders(): void
    {
        /** @var \OzeFramework\Http\Response $response */
        $response = $this->responseFactory->createResponse();
        $response = $response->withHeader('X-Custom-Header', 'Value');

        $this->assertSame(['Value'], $response->getHeader('X-Custom-Header'));
    }

    public function testMessageChecksForExistingHeader(): void
    {
        /** @var \OzeFramework\Http\Response $response */
        $response = $this->responseFactory->createResponse();
        $response = $response->withHeader('X-Custom-Header', 'Value');

        $this->assertTrue($response->hasHeader('X-Custom-Header'));
        $this->assertFalse($response->hasHeader('Non-Existing-Header'));
    }

    public function testMessageReturnsHeaderLine(): void
    {
        /** @var \OzeFramework\Http\Response $response */
        $response = $this->responseFactory->createResponse();
        $response = $response->withHeader('X-Custom-Header', 'Value');

        $this->assertSame('Value', $response->getHeaderLine('X-Custom-Header'));
    }

    public function testMessageReturnsBody(): void
    {
        /** @var \OzeFramework\Http\Response $response */
        $response = $this->responseFactory->createResponse();

        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
    }

    public function testMessageReturnsNewObjectWithModifiedProtocolVersion(): void
    {
        $response = $this->responseFactory->createResponse();
        $newResponse = $response->withProtocolVersion('2.0');

        $this->assertSame('2.0', $newResponse->getProtocolVersion());
        $this->assertNotSame($response, $newResponse);
    }

    public function testMessageReturnsNewObjectWithModifiedHeader(): void
    {
        /** @var \OzeFramework\Http\Response $response */
        $response = $this->responseFactory->createResponse();
        $newResponse = $response->withHeader('X-New-Header', 'NewValue');

        $this->assertSame(['NewValue'], $newResponse->getHeader('X-New-Header'));
        $this->assertNotSame($response, $newResponse);
    }

    public function testMessageReturnsNewObjectWithAddedHeader(): void
    {
        /** @var \OzeFramework\Http\Response $response */
        $response = $this->responseFactory->createResponse();
        $response = $response->withHeader('X-Custom-Header', 'Value');
        $newResponse = $response->withAddedHeader('X-Custom-Header', 'AdditionalValue');

        $this->assertSame(['Value', 'AdditionalValue'], $newResponse->getHeader('X-Custom-Header'));
        $this->assertNotSame($response, $newResponse);
    }

    public function testMessageReturnsNewObjectWithoutHeader(): void
    {
        /** @var \OzeFramework\Http\Response $response */
        $response = $this->responseFactory->createResponse();
        $response = $response->withHeader('X-Remove-Header', 'Value');
        $newResponse = $response->withoutHeader('X-Remove-Header');

        $this->assertFalse($newResponse->hasHeader('X-Remove-Header'));
        $this->assertNotSame($response, $newResponse);
    }

    public function testMessageReturnsNewObjectWithModifiedBody(): void
    {
        /** @var \OzeFramework\Http\Response $response */
        $response = $this->responseFactory->createResponse();
        $newBody = $this->streamFactory->createStream('Hello, OzeFramework!');
        $newResponse = $response->withBody($newBody);

        $this->assertSame($newBody, $newResponse->getBody());
        $this->assertNotSame($response, $newResponse);
    }
}
