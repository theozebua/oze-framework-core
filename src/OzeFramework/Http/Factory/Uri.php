<?php

declare(strict_types=1);

namespace OzeFramework\Http\Factory;

use InvalidArgumentException;
use OzeFramework\Http\Uri as HttpUri;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

use function parse_url;

class Uri implements UriFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createUri(string $uri = ''): UriInterface
    {
        $parts = parse_url($uri);

        if ($parts === false) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid URI', $uri));
        }

        return new HttpUri(
            scheme: $parts['scheme'] ?? null,
            host: $parts['host'] ?? null,
            port: $parts['port'] ?? null,
            user: $parts['user'] ?? null,
            password: $parts['pass'] ?? null,
            path: $parts['path'] ?? null,
            query: $parts['query'] ?? null,
            fragment: $parts['fragment'] ?? null,
        );
    }

    /**
     * Create a new URI.
     *
     * @param string $uri
     * @throws InvalidArgumentException If the given URI cannot be parsed.
     * @return UriInterface
     */
    public static function create(string $uri = ''): UriInterface
    {
        return (new static())->createUri($uri);
    }
}
