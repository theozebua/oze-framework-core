<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use InvalidArgumentException;
use OzeFramework\Http\Exceptions\UnsupportedHttpProtocolVersion;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class Message implements MessageInterface
{
    /**
     * @var string[]
     */
    protected const array SUPPORTED_PROTOCOLS = ['1.1', '2.0'];

    /**
     * @var string $protocolVersion
     */
    protected string $protocolVersion = '1.1';

    /**
     * @var Headers $headers
     */
    protected Headers $headers;

    /**
     * @var StreamInterface $body
     */
    protected StreamInterface $body;

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * {@inheritdoc}
     * 
     * The version string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
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
     * 
     * This method returns an array of all the header values of the given
     * case-insensitive header name.
     * 
     * @return string[] An array of string values as provided for the given header.
     */
    public function getHeader(string $name): array
    {
        return $this->headers->getHeader($name)->values;
    }

    /**
     * {@inheritdoc}
     * 
     * This method returns all of the header values of the given 
     * case-insensitive header name as a string concatenated together using 
     * a comma.
     * 
     * NOTE: Not all header values may be appropriately represented using 
     * comma concatenation. For such headers, use getHeader() instead 
     * and supply your own delimiter when concatenating.
     * 
     * @return string A string of values as provided for the given header 
     * concatenated together using a comma.
     */
    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    /**
     * {@inheritdoc}
     * 
     * While header names are case-insensitive, the casing of the header will be preserved by this function, and returned from getHeaders().
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        $this->validateHeaderValue($value);

        $clone = clone $this;

        $clone->headers->setHeader($name, $value);

        $this->addResponseHeader($clone, $name);

        return $clone;
    }

    /**
     * {@inheritdoc}
     * 
     * Existing values for the specified header will be maintained. The new 
     * value(s) will be appended to the existing list. If the header did not 
     * exist previously, it will be added.
     */
    public function withAddedHeader(string $name, $value): MessageInterface
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
     * 
     * Header resolution MUST be done without case-sensitivity.
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
     * 
     * The body MUST be a StreamInterface object.
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
     * @param mixed $value 
     * @throws InvalidArgumentException
     * @return void
     */
    protected function validateHeaderValue(mixed $value): void
    {
        if (!is_string($value) || !is_array($value)) {
            throw new InvalidArgumentException('Header value must be a string or an array of strings; received ' . get_debug_type($value));
        }
    }

    /**
     * Add response header.
     * 
     * @param MessageInterface $clone
     * @param string $name
     * @return void
     */
    protected function addResponseHeader(MessageInterface $clone, string $name): void
    {
        if ($clone instanceof ResponseInterface) {
            header(sprintf('%s: %s', $name, $clone->getHeaderLine($name)));
        }
    }

    /**
     * Remove response header.
     * 
     * @param MessageInterface $clone
     * @param string $name
     * @return void
     */
    protected function removeResponseHeader(MessageInterface $clone, string $name): void
    {
        if ($clone instanceof ResponseInterface) {
            header_remove($name);
        }
    }
}
