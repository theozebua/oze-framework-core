<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use Psr\Http\Message\ServerRequestInterface;

class ServerRequest extends Request implements ServerRequestInterface
{
    /**
     * {@inheritdoc}
     */
    public function getServerParams(): array
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getCookieParams(): array
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParams(): array
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadedFiles(): array
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedBody()
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(): array
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute(string $name, $default = null)
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function withoutAttribute(string $name): ServerRequestInterface
    {
        throw new \Exception('Not implemented.');
    }
}
