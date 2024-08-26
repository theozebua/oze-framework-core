<?php

declare(strict_types=1);

namespace OzeFramework\Http\Factory;

use InvalidArgumentException;
use OzeFramework\Http\Uri as HttpUri;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

use function count;
use function explode;
use function parse_url;
use function preg_match;
use function strpos;
use function strstr;
use function substr;

use const PHP_URL_QUERY;

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
     * Create a new URI from global variables.
     *
     * @param array $globals
     * @return UriInterface
     */
    public function createFromGlobals(array $globals): UriInterface
    {
        $https = $globals['HTTPS'] ?? false;
        $scheme = !$https || $https === 'off' ? 'http' : 'https';

        $username = $globals['PHP_AUTH_USER'] ?? '';
        $password = $globals['PHP_AUTH_PW'] ?? '';

        $host = $globals['HTTP_HOST'] ?? ($globals['SERVER_NAME'] ?? '');

        $port = !empty($globals['SERVER_PORT']) ? (int) $globals['SERVER_PORT'] : ($scheme === 'https' ? 443 : 80);

        if (preg_match('/^(\[[a-fA-F0-9:.]+])(:\d+)?\z/', $host, $matches)) {
            $host = $matches[1];

            if (isset($matches[2])) {
                $port = (int) substr($matches[2], 1);
            }
        } else {
            $pos = strpos($host, ':');

            if ($pos !== false) {
                $port = (int) substr($host, $pos + 1);
                $host = strstr($host, ':', true);
            }
        }

        $requestUri = $globals['REQUEST_URI'] ?? '/';
        $uriFragments = explode('?', $requestUri);
        $path = $uriFragments[0];
        $query = $globals['QUERY_STRING'] ?? '';

        if ($query === '' && count($uriFragments) > 1) {
            $query = parse_url('https://example.com' . $requestUri, PHP_URL_QUERY) ?? '';
        }

        return new HttpUri(
            scheme: $scheme,
            host: $host,
            port: $port,
            user: $username,
            password: $password,
            path: $path,
            query: $query,
        );
    }
}
