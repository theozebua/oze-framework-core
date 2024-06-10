<?php

declare(strict_types=1);

namespace OzeFramework\Http\Contracts;

use OzeFramework\Http\Header;

interface Headers
{
    /**
     * Get all headers.
     *
     * @return Header[]
     */
    public function getHeaders(): array;

    /**
     * Get header by key.
     */
    public function getHeader(string $key): ?Header;

    /**
     * Check if header exists.
     */
    public function hasHeader(string $key): bool;

    /**
     * Add header.
     */
    public function addHeader(string $key, array|string $values): Headers;

    /**
     * Add headers from array.
     */
    public function addHeaders(array $headers): Headers;

    /**
     * Remove header by key.
     */
    public function removeHeader(string $key): Headers;

    /**
     * Set or replace existing header with new values.
     */
    public function setHeader(string $key, array|string $values): Headers;

    /**
     * Set or replace existing headers with new values.
     */
    public function setHeaders(array $headers): Headers;
}
