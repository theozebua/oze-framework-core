<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use Psr\Http\Message\StreamInterface;
use RuntimeException;

use function fclose;
use function feof;
use function fread;
use function fseek;
use function fstat;
use function ftell;
use function fwrite;
use function is_resource;
use function stream_get_contents;
use function stream_get_meta_data;
use function strpos;

use const SEEK_SET;

class Stream implements StreamInterface
{
    /**
     * @var int|null
     */
    protected ?int $size = null;

    /**
     * Create a new stream instance.
     *
     * @param mixed $resource
     * @throws RuntimeException if the provided resource is not valid.
     * @return void
     */
    public function __construct(protected mixed $resource = null)
    {
        if (!is_null($this->resource) && !is_resource($this->resource)) {
            throw new RuntimeException('Invalid resource provided.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        if ($this->isSeekable()) {
            $this->rewind();
        }

        try {
            return $this->getContents();
        } catch (RuntimeException) {
            return '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
        if (!is_null($this->resource)) {
            fclose($this->resource);

            $this->resource = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function detach(): mixed
    {
        $result = $this->resource;

        $this->resource = null;
        $this->size = null;

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): ?int
    {
        if (!is_null($this->size)) {
            return $this->size;
        }

        if (is_null($this->resource)) {
            return null;
        }

        $stats = fstat($this->resource);

        $this->size = $stats['size'] ?? null;

        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function tell(): int
    {
        if (is_null($this->resource)) {
            throw new RuntimeException('No resource available; cannot tell position.');
        }

        $result = ftell($this->resource);

        if ($result === false) {
            throw new RuntimeException('Unable to determine stream position.');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function eof(): bool
    {
        return is_null($this->resource) || feof($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable(): bool
    {
        if (is_null($this->resource)) {
            return false;
        }

        $meta = $this->getMetadata();

        return $meta['seekable'] ?? false;
    }

    /**
     * {@inheritdoc}
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->isSeekable() || fseek($this->resource, $offset, $whence) === -1) {
            throw new RuntimeException('Unable to seek in stream.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable(): bool
    {
        if (is_null($this->resource)) {
            return false;
        }

        $meta = $this->getMetadata();
        $mode = $meta['mode'] ?? '';

        return strpos($mode, 'w') !== false || strpos($mode, '+') !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $string): int
    {
        if (!$this->isWritable()) {
            throw new RuntimeException('Stream is not writable.');
        }

        $result = fwrite($this->resource, $string);

        if ($result === false) {
            throw new RuntimeException('Unable to write to stream.');
        }

        $this->size = null;

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable(): bool
    {
        if (is_null($this->resource)) {
            return false;
        }

        $meta = $this->getMetadata();
        $mode = $meta['mode'] ?? '';

        return strpos($mode, 'r') !== false || strpos($mode, '+') !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function read(int $length): string
    {
        if (!$this->isReadable()) {
            throw new RuntimeException('Stream is not readable.');
        }

        $result = fread($this->resource, $length);

        if ($result === false) {
            throw new RuntimeException('Unable to read from stream.');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getContents(): string
    {
        if (!$this->isReadable()) {
            throw new RuntimeException('Cannot get contents of non-readable stream.');
        }

        $result = stream_get_contents($this->resource);

        if ($result === false) {
            throw new RuntimeException('Unable to get stream contents.');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(?string $key = null): mixed
    {
        if (is_null($this->resource)) {
            return $key ? null : [];
        }

        $meta = stream_get_meta_data($this->resource);

        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }
}
