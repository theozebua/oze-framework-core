<?php

declare(strict_types=1);

namespace OzeFramework\Request;

use OzeFramework\Exceptions\Request\KeyNotFoundException;
use OzeFramework\Interfaces\Request\FileUploadsInterface;

final class FileUploads implements FileUploadsInterface
{
    /**
     * Uploaded file(s).
     * 
     * @var array<string, mixed> $files
     */
    private array $files;

    /**
     * Create FileUploads instance.
     * 
     * @param null|string $key
     * 
     * @return void
     */
    final public function __construct(?string $key = null)
    {
        $this->files = $_FILES;

        if (!is_null($key)) {
            if (!$this->has($key)) {
                throw new KeyNotFoundException("Key {$key} is not found");
            }

            $this->files = $this->files[$key];
        }
    }

    /**
     * {@inheritdoc}
     */
    final public function has(string $key): bool
    {
        return isset($this->files[$key]);
    }

    /**
     * {@inheritdoc}
     */
    final public function getFileOriginalName(): mixed
    {
        return $this->files['name'];
    }

    /**
     * {@inheritdoc}
     */
    final public function getFileOriginalMimeType(): mixed
    {
        return $this->files['type'];
    }

    /**
     * {@inheritdoc}
     */
    final public function getFileOriginalSize(): mixed
    {
        return $this->files['size'];
    }

    /**
     * {@inheritdoc}
     */
    final public function getFileOriginalExtension(): mixed
    {
        $nameAndExtension = explode('.', $this->getFileOriginalName());

        return end($nameAndExtension);
    }
}