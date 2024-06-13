<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use InvalidArgumentException;
use OzeFramework\Http\Factory\Uri;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    protected string $url = 'http://username:password@hostname:9090/path?arg=value#anchor';

    public function testUriFactoryThrowsInvalidArgumentExceptionIfGivenUriIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Uri::create('http:///invalid.com');
    }

    // TODO: Add more tests
}
