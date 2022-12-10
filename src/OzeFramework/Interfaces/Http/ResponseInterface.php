<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\Http;

interface ResponseInterface
{
    /**
     * Set status code.
     * 
     * @param int $code
     * 
     * @return int|bool
     */
    public function statusCode(int $code): int|bool;

    /**
     * Redirect to another route.
     * 
     * @param string $route
     * @param int $code
     * 
     * @return void
     */
    public function redirect(string $route, int $code): void;

    /**
     * Set single header.
     * 
     * @param string $key
     * @param mixed $value
     * @param bool $replace
     * @param int $code
     * 
     * @return void
     */
    public function header(string $key, mixed $value, bool $replace = true, int $code = 200): void;

    /**
     * Set multiple headers.
     * 
     * @param array<string, mixed> $headers
     * 
     * @return void
     */
    public function headers(array $headers): void;
}
