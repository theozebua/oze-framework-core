<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use InvalidArgumentException;
use OzeFramework\Http\Exceptions\UnsupportedHttpProtocolVersion;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

use function get_debug_type;
use function header;
use function header_remove;
use function implode;
use function in_array;
use function is_array;
use function is_string;
use function sprintf;

abstract class Message implements MessageInterface
{
    /**
     * Supported HTTP protocol versions.
     *
     * @var string[]
     */
    protected const array SUPPORTED_PROTOCOLS = ['1.1', '2.0'];

    /**
     * Construct a new http message.
     *
     * @param string $protocolVersion
     * @param Headers $headers
     * @param StreamInterface $body
     * @return void
     */
    public function __construct(
        protected string $protocolVersion = '1.1',
        protected Headers $headers = new Headers(),
        protected StreamInterface $body = new Stream(),
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        if (!in_array($version, self::SUPPORTED_PROTOCOLS)) {
            throw new UnsupportedHttpProtocolVersion("Protocol version [{$version}] is not supported. Supported versions: " . implode(', ', self::SUPPORTED_PROTOCOLS));
        }

        $clone = clone $this;

        $clone->protocolVersion = $version;

        return $clone;
    }

    /**
     * Retrieves all message header values.
     *
     * It returns an array of `OzeFramework\Http\Header` instances.
     *
     * @return Header[]
     */
    public function getHeaders(): array
    {
        return $this->headers->getHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader(string $name): bool
    {
        return $this->headers->hasHeader($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader(string $name): array
    {
        return $this->headers->getHeader($name)?->values ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    /**
     * {@inheritdoc}
     */
    public function withHeader(string $name, mixed $value): MessageInterface
    {
        $this->validateHeaderValue($value);

        $clone = clone $this;

        $clone->headers->setHeader($name, $value);

        $this->addResponseHeader($clone, $name);

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withAddedHeader(string $name, mixed $value): MessageInterface
    {
        $this->validateHeaderValue($value);

        $clone = clone $this;

        if (!$clone->headers->hasHeader($name)) {
            $clone->headers->addHeader($name, $value);
        } else {
            $values = $clone->getHeader($name);

            if (!in_array($value, $values)) {
                $values[] = $value;

                $clone->headers->setHeader($name, $values);
            }
        }

        $this->addResponseHeader($clone, $name);

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutHeader(string $name): MessageInterface
    {
        $clone = clone $this;

        $clone->headers->removeHeader($name);

        $this->removeResponseHeader($clone, $name);

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        $clone = clone $this;

        $clone->body = $body;

        return $clone;
    }

    /**
     * Validate header value.
     *
     * @throws InvalidArgumentException
     */
    protected function validateHeaderValue(mixed $value): void
    {
        if (!is_string($value) && !is_array($value)) {
            throw new InvalidArgumentException('Header value must be a string or an array of strings; received ' . get_debug_type($value));
        }
    }

    /**
     * Add response header.
     */
    protected function addResponseHeader(MessageInterface $messageInterface, string $name): void
    {
        if ($messageInterface instanceof ResponseInterface) {
            header(sprintf('%s: %s', $name, $messageInterface->getHeaderLine($name)));
        }
    }

    /**
     * Remove response header.
     */
    protected function removeResponseHeader(MessageInterface $messageInterface, string $name): void
    {
        if ($messageInterface instanceof ResponseInterface) {
            header_remove($name);
        }
    }
}
