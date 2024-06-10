<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use Exception;
use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
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
    public function close(): void
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): ?int
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function tell(): int
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function eof(): bool
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable(): bool
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable(): bool
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $string): int
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable(): bool
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function read(int $length): string
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function getContents(): string
    {
        throw new Exception('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(?string $key = null)
    {
        throw new Exception('Not implemented');
    }
}
