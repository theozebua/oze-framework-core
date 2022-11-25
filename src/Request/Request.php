<?php

declare(strict_types=1);

namespace Theozebua\OzeFramework\Request;

use Theozebua\OzeFramework\Exceptions\Request\KeyNotFoundException;
use Theozebua\OzeFramework\Interfaces\Request\RequestInterface;

final class Request implements RequestInterface
{
    /**
     * {@inheritdoc}
     */
    final public function get(?string $key = null): mixed
    {
        if (isset($key)) {
            if (!isset($_GET[$key])) {
                throw new KeyNotFoundException("Key {$key} is not found");
            }

            return $_GET[$key];
        }

        return $_GET;
    }

    /**
     * {@inheritdoc}
     */
    final public function post(?string $key = null): mixed
    {
        if (isset($key)) {
            if (!isset($_POST[$key])) {
                throw new KeyNotFoundException("Key {$key} is not found");
            }

            return $_POST[$key];
        }

        return $_POST;
    }

    /**
     * {@inheritdoc}
     */
    final public function getQueryString(string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * {@inheritdoc}
     */
    final public function getQueryStringAll(): array
    {
        return $this->get();
    }

    /**
     * {@inheritdoc}
     */
    final public function input(string $key): mixed
    {
        return $this->post($key);
    }

    /**
     * {@inheritdoc}
     */
    final public function inputAll(): array
    {
        return $this->post();
    }
}
