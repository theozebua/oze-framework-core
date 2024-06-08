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
     * 
     * @param string $key
     * @return null|Header
     */
    public function getHeader(string $key): ?Header;

    /**
     * Check if header exists.
     * 
     * @param string $key
     * @return bool
     */
    public function hasHeader(string $key): bool;

    /**
     * Add header.
     * 
     * @param string $key
     * @param string|array $values
     * @return Headers
     */
    public function addHeader(string $key, string|array $values): Headers;

    /**
     * Add headers from array.
     * 
     * @param array $headers
     * @return Headers
     */
    public function addHeaders(array $headers): Headers;

    /**
     * Remove header by key.
     * 
     * @param string $key
     * @return Headers
     */
    public function removeHeader(string $key): Headers;

    /**
     * Set or replace existing header with new values.
     * 
     * @param string $key
     * @param string|array $values
     * @return Headers
     */
    public function setHeader(string $key, string|array $values): Headers;

    /**
     * Set or replace existing headers with new values.
     * 
     * @param array $headers
     * @return Headers
     */
    public function setHeaders(array $headers): Headers;
}
