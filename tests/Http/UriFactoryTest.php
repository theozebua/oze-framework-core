<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use InvalidArgumentException;
use OzeFramework\Http\Factory\Uri;
use Psr\Http\Message\UriInterface;

final class UriFactoryTest extends AbstractUri
{
    public function testUriFactoryThrowsInvalidArgumentExceptionIfGivenUriIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Uri::create('http:///invalid.com');
    }

    public function testUriFactoryReturnsUriObjectThatImplementsPsrHttpMessageUriInterface(): void
    {
        $uri = Uri::create($this->url);

        $this->assertInstanceOf(UriInterface::class, $uri);
    }
}
