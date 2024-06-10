<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRequestTarget(): string
    {
        throw new Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        throw new Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): string
    {
        throw new Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function withMethod(string $method): RequestInterface
    {
        throw new Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getUri(): UriInterface
    {
        throw new Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        throw new Exception('Not implemented.');
    }
}
