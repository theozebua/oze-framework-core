<?php

declare(strict_types=1);

namespace Theozebua\OzeFramework\Interfaces\Request;

use Theozebua\OzeFramework\Exceptions\Request\KeyNotFoundException;

interface RequestInterface
{
    /**
     * Get data from GET request.
     * 
     * @param null|string $key
     * 
     * @throws KeyNotFoundException
     * 
     * @return mixed
     */
    public function get(?string $key = null): mixed;

    /**
     * Get data from POST request.
     * 
     * @param null|string $key
     * 
     * @throws KeyNotFoundException
     * 
     * @return mixed
     */
    public function post(?string $key = null): mixed;

    /**
     * Get data from query string.
     * 
     * @param string $key
     * 
     * @return mixed
     */
    public function getQueryString(string $key): mixed;

    /**
     * Get all data from query string.
     * 
     * @return array
     */
    public function getQueryStringAll(): array;

    /**
     * Get data from form input.
     * 
     * @param string $key
     * 
     * @return mixed
     */
    public function input(string $key): mixed;

    /**
     * Get all data from form input.
     * 
     * @return array
     */
    public function inputAll(): array;
}
