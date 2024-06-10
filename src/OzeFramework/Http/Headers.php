<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use OzeFramework\Http\Contracts\Headers as HeadersContract;

class Headers implements HeadersContract
{
    /**
     * Create a new headers instance.
     *
     * @param  Header[]  $headers
     * @return void
     */
    public function __construct(protected array $headers = [])
    {
        //
    }

    public static function createFromGlobals(): static
    {
        $headers = [];

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        }

        if (! is_array($headers)) {
            $headers = [];
        }

        // ? Is Content-Type and Content-Length available?
        // * See https://www.php.net/manual/en/reserved.variables.server.php#110763
        if (empty($headers)) {
            $headers = array_filter($_SERVER, fn (string $key): bool => str_starts_with($key, 'HTTP_'), ARRAY_FILTER_USE_KEY);
        }

        $headers = array_map(fn (string $key, string|array $values): Header => new Header($key, $this->wrap($values)), array_keys($headers), $headers);

        return new static($headers);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader(string $key): ?Header
    {
        foreach ($this->headers as $header) {
            if (strtolower($header->key) === strtolower($key)) {
                return $header;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader(string $key): bool
    {
        return $this->getHeader($key) !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function addHeader(string $key, string|array $values): HeadersContract
    {
        $this->headers[] = new Header($key, $this->wrap($values));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addHeaders(array $headers): HeadersContract
    {
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeHeader(string $key): HeadersContract
    {
        foreach ($this->headers as $index => $header) {
            if ($header->key === $key) {
                unset($this->headers[$index]);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeader(string $key, string|array $values): HeadersContract
    {
        foreach ($this->headers as $index => $header) {
            if ($header->key === $key) {
                $this->headers[$index] = new Header($key, $this->wrap($values));
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeaders(array $headers): HeadersContract
    {
        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value);
        }

        return $this;
    }

    /**
     * Wrap value into array if it's not already.
     *
     * @return string[]
     */
    protected function wrap(string|array $value): array
    {
        return is_string($value) ? [$value] : $value;
    }
}
