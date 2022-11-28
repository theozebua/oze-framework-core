<?php

declare(strict_types=1);

namespace Theozebua\OzeFramework\Interfaces\Request;

interface FileUploadsInterface
{
    /**
     * Check if uploaded file with the given key is exists.
     * 
     * @param string $key
     * 
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get file original name.
     * 
     * @return mixed
     */
    public function getFileOriginalName(): mixed;

    /**
     * Get file original mime type.
     * 
     * @return mixed
     */
    public function getFileOriginalMimeType(): mixed;

    /**
     * Get file original size.
     * 
     * @return mixed
     */
    public function getFileOriginalSize(): mixed;

    /**
     * Get file original extension.
     * 
     * @return mixed
     */
    public function getFileOriginalExtension(): mixed;
}
