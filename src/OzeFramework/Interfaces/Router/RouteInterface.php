<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\Router;

use Closure;

interface RouteInterface
{
    /**
     * Register a new GET route with the router.
     *
     * @param string $uri
     * @param Closure|array<string, string> $action
     * 
     * @return void
     */
    public function get(string $uri, Closure|array $action): void;

    /**
     * Register a new POST route with the router.
     *
     * @param string $uri
     * @param Closure|array<string, string> $action
     * 
     * @return void
     */
    public function post(string $uri, Closure|array $action): void;

    /**
     * Register a new PUT route with the router.
     *
     * @param string $uri
     * @param Closure|array<string, string> $action
     * 
     * @return void
     */
    public function put(string $uri, Closure|array $action): void;

    /**
     * Register a new PATCH route with the router.
     *
     * @param string $uri
     * @param Closure|array<string, string> $action
     * 
     * @return void
     */
    public function patch(string $uri, Closure|array $action): void;

    /**
     * Register a new DELETE route with the router.
     *
     * @param string $uri
     * @param Closure|array<string, string> $action
     * 
     * @return void
     */
    public function delete(string $uri, Closure|array $action): void;
}
