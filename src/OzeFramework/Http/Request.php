<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use OzeFramework\Exceptions\Http\KeyNotFoundException;
use OzeFramework\Interfaces\Http\RequestInterface;
use OzeFramework\Http\Response;

final class Request implements RequestInterface
{
    /**
     * The Response class.
     * 
     * @var Response $response
     */
    private Response $response;

    /**
     * Create a Request instance.
     * 
     * @return void
     */
    final public function __construct()
    {
        $this->response = new Response();
    }

    /**
     * {@inheritdoc}
     */
    final public function get(?string $key = null): mixed
    {
        if (isset($key)) {
            if (!isset($_GET[$key])) {
                $this->response->statusCode(Response::INTERNAL_SERVER_ERROR);
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
                $this->response->statusCode(Response::INTERNAL_SERVER_ERROR);
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

    /**
     * Get uploaded file(s).
     * 
     * @param null|string $key
     * 
     * @return FileUploads
     */
    final public function file(?string $key = null): FileUploads
    {
        return new FileUploads($key);
    }
}
