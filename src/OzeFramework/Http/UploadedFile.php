<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use Exception;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFile implements UploadedFileInterface
{
    /**
     * {@inheritdoc}
     */
    public function getStream(): StreamInterface
    {
        throw new Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function moveTo(string $targetPath): void
    {
        throw new Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): ?int
    {
        throw new Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getError(): int
    {
        throw new Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getClientFilename(): ?string
    {
        throw new Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getClientMediaType(): ?string
    {
        throw new Exception('Not implemented.');
    }
}
