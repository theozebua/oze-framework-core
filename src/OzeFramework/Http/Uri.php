<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use Exception;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use SensitiveParameter;

use function array_key_exists;
use function array_keys;
use function is_null;
use function ltrim;
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
        throw new Exception('Not implemented');
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
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function withHost(string $host): UriInterface
    {
        throw new Exception('Not implemented');
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
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function withFragment(string $fragment): UriInterface
    {
        throw new Exception('Not implemented');
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
                array_keys(self::SUPPORTED_SCHEMES),
            ));
        }
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
     * @param null|string &$path
     * @throws InvalidArgumentException
     * @return void
     */
    protected function validatePath(?string &$path): void
    {
        // TODO
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
