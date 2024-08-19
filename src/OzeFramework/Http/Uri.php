<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use SensitiveParameter;

use function array_key_exists;
use function array_keys;
use function filter_var;
use function implode;
use function is_null;
use function is_string;
use function ltrim;
use function preg_match;
use function preg_replace;
use function preg_replace_callback;
use function rawurlencode;
use function sprintf;
use function str_starts_with;
use function strtolower;

class Uri implements UriInterface
{
    /**
     * Supported schemes.
     *
     * @var array<string, int>
     */
    protected const array SUPPORTED_SCHEMES = [
        'http' => 80,
        'https' => 443,
    ];

    /**
     * Valid TCP/UDP port range.
     *
     * @var int[]
     */
    protected const VALID_PORT_RANGE = [1, 65535];

    /**
     * Create a new URI instance.
     *
     * @param null|string $scheme
     * @param null|string $host
     * @param null|int $port
     * @param null|string $user
     * @param null|string $password
     * @param null|string $path
     * @param null|string $query
     * @param null|string $fragment
     */
    public function __construct(
        protected ?string $scheme = null,
        protected ?string $host = null,
        protected ?int $port = null,
        protected ?string $user = null,
        protected ?string $password = null,
        protected ?string $path = null,
        protected ?string $query = null,
        protected ?string $fragment = null,
    ) {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        $uri = '';
        $scheme = $this->getScheme();
        $authority = $this->getAuthority();
        $path = $this->getPath();
        $query = $this->getQuery();
        $fragment = $this->getFragment();

        if ($scheme !== '') {
            $uri .= sprintf('%s:', $scheme);
        }

        if ($authority !== '') {
            $uri .= sprintf('//%s', $authority);
        }

        if ($path !== '') {
            if ($authority !== '' && $path[0] !== '/') {
                // If authority is present and path is rootless, prefix with "/"
                $path = '/' . $path;
            }
            // Reduce multiple slashes to a single one if no authority is present
            $path = preg_replace('/\/+/', '/', $path);
            $uri .= $path;
        }

        if ($query !== '') {
            $uri .= sprintf('?%s', $query);
        }

        if ($fragment !== '') {
            $uri .= sprintf('#%s', $fragment);
        }

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme(): string
    {
        return $this->scheme ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthority(): string
    {
        $host = $this->getHost();
        $port = $this->getPort();
        $userInfo = $this->getUserInfo();

        $port = !is_null($port) ? sprintf(':%s', $port) : '';
        $userInfo = $userInfo !== '' ? sprintf('%s@', $userInfo) : '';

        return $userInfo . $host . $port;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo(): string
    {
        $userInfo = $this->user ?? '';

        if (!is_null($this->password)) {
            $userInfo .= sprintf(':%s', $this->password);
        }

        return $userInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost(): string
    {
        return $this->host ? strtolower($this->host) : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getPort(): ?int
    {
        return !$this->hasStandardPort() ? $this->port : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        if (str_starts_with($this->path, '/')) {
            return '/' . ltrim($this->path, '/');
        }

        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * {@inheritdoc}
     */
    public function withScheme(string $scheme): UriInterface
    {
        $this->validateScheme($scheme);

        $clone = clone $this;

        $clone->scheme = $scheme;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withUserInfo(string $user, #[SensitiveParameter] ?string $password = null): UriInterface
    {
        $this->filterUserInfo($user, $password);

        $clone = clone $this;

        $clone->user = $user;
        $clone->password = $password;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withHost(string $host): UriInterface
    {
        $this->validateHost($host);

        $clone = clone $this;

        $clone->host = $host;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withPort(?int $port): UriInterface
    {
        $this->validatePort($port);

        $clone = clone $this;

        $clone->port = $port;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withPath(string $path): UriInterface
    {
        $this->validatePath($path);

        $clone = clone $this;

        $clone->path = $path;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery(string $query): UriInterface
    {
        $this->validateQuery($query);

        $clone = clone $this;

        $clone->query = $query;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withFragment(string $fragment): UriInterface
    {
        $clone = clone $this;

        $clone->fragment = $fragment;

        return $clone;
    }

    /**
     * Validate the scheme.
     *
     * @param string &$scheme E.g. http, https
     * @throws InvalidArgumentException
     * @return void
     */
    protected function validateScheme(string &$scheme): void
    {
        $scheme = strtolower($scheme);

        if (!array_key_exists($scheme, self::SUPPORTED_SCHEMES)) {
            throw new InvalidArgumentException(sprintf(
                'Scheme "%s" is not supported. Supported schemes: %s',
                $scheme,
                implode(', ', array_keys(self::SUPPORTED_SCHEMES)),
            ));
        }
    }

    /**
     * Filter the given user and password.
     *
     * @param null|string &$user
     * @param null|string &$password
     * @return void
     */
    protected function filterUserInfo(?string &$user, #[SensitiveParameter] ?string &$password): void
    {
        $filter = function (?string &$info): string {
            if (is_null($info)) {
                return '';
            }

            $match =  preg_replace_callback(
                '/(?:[^%a-zA-Z0-9_\-\.~\pL!\$&\'\(\)\*\+,;=]+|%(?![A-Fa-f0-9]{2}))/u',
                function (array $matches): string {
                    return rawurlencode($matches[0]);
                },
                $info,
            );

            return is_string($match) ? $match : '';
        };

        $filter($user);
        $filter($password);
    }

    /**
     * Validate the given host.
     *
     * @param string &$host
     * @throws InvalidArgumentException
     * @return void
     */
    protected function validateHost(string &$host): void
    {
        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return;
        }

        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $host = sprintf('[%s]', $host);

            return;
        }

        if (
            filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) &&
            preg_match('/^(?=.{1,253}$)(?:(?!\d+\.?$)[a-zA-Z0-9-_]{1,63}\.?)+(?:[a-zA-Z]{2,})$/', $host) === 1
        ) {
            return;
        }

        throw new InvalidArgumentException('Host "' . $host . '" is not valid. Please use a valid host name.');
    }

    /**
     * Validate the given port.
     *
     * @param null|int &$port
     * @throws InvalidArgumentException
     * @return void
     */
    protected function validatePort(?int &$port): void
    {
        if (is_null($port)) {
            $port = null;

            return;
        }

        if ($port < self::VALID_PORT_RANGE[0] || $port > self::VALID_PORT_RANGE[1]) {
            throw new InvalidArgumentException(sprintf(
                'Port "%s" is not supported. Supported ports: %d-%d',
                $port,
                self::VALID_PORT_RANGE[0],
                self::VALID_PORT_RANGE[1],
            ));
        }
    }

    /**
     * Validate the given path.
     *
     * @param string &$path
     * @throws InvalidArgumentException
     * @return void
     */
    protected function validatePath(string &$path): void
    {
        if (preg_match('/[?#]/', $path)) {
            throw new InvalidArgumentException('Path cannot contain query (?) or fragment (#) delimiters.');
        }

        if (preg_match('/%[^0-9A-Fa-f]{2}/', $path)) {
            throw new InvalidArgumentException('Path contains an invalid percent-encoded sequence.');
        }

        $path = preg_replace_callback(
            '/(?:[^a-zA-Z0-9_\-\.~:@&=\+\$,\/;%]+|%(?![A-Fa-f0-9]{2}))/',
            fn (array $match): string => rawurlencode($match[0]),
            $path,
        );

        $path = is_string($path) ? $path : '';
    }

    /**
     * Validate the given query.
     *
     * @param string &$query
     * @throws InvalidArgumentException
     * @return void
     */
    protected function validateQuery(string &$query): void
    {
        if (preg_match('/[?#]/', $query)) {
            throw new InvalidArgumentException('Query cannot contain query (?) or fragment (#) delimiters.');
        }

        if (preg_match('/%[^0-9A-Fa-f]{2}/', $query)) {
            throw new InvalidArgumentException('Query contains an invalid percent-encoded sequence.');
        }

        $query = preg_replace_callback(
            '/(?:[^a-zA-Z0-9_\-\.~!\$&\'\(\)\*\+,;=%:@\/\?]+|%(?![A-Fa-f0-9]{2}))/',
            fn (array $match): string => rawurlencode($match[0]),
            $query,
        );

        $query = is_string($query) ? $query : '';
    }

    /**
     * Filter the given fragment.
     *
     * @param string &$fragment
     * @return void
     */
    protected function filterFragment(string &$fragment): void
    {
        $fragment = ltrim($fragment, '#');

        $fragment = preg_replace_callback(
            '/(?:[^a-zA-Z0-9_\-\.~!\$&\'\(\)\*\+,;=%:@\/\?]+|%(?![A-Fa-f0-9]{2}))/',
            fn (array $match): string => rawurlencode($match[0]),
            $fragment,
        );

        $fragment = is_string($fragment) ? $fragment : '';
    }

    /**
     * Determine if the URI has the standard port.
     *
     * @return bool
     */
    protected function hasStandardPort(): bool
    {
        return !is_null($this->port) && $this->port === self::SUPPORTED_SCHEMES[$this->scheme];
    }
}
