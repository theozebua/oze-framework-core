<?php

declare(strict_types=1);

namespace OzeFramework\Http\Contracts;

use Closure;
use OzeFramework\Http\Enums\HttpMethod;
use OzeFramework\Http\Handler;

interface RouteRegistrar
{
    /**
     * Register a new route.
     *
     * @param HttpMethod $httpMethod
     * @param string $uri
     * @param Closure|Handler $handler
     * @return self
     */
    public function registerRoute(HttpMethod $httpMethod, string $uri, Closure|Handler $handler): self;

    /**
     * Register routes from controller attributes.
     *
     * @param class-string<Controller>[] $controllers
     * @return void
     */
    public function registerRoutesFromControllerAttribute(array $controllers): void;

    /**
     * Register a new GET route.
     *
     * @param string $uri
     * @param Closure|Handler $handler
     * @return self
     */
    public function get(string $uri, Closure|Handler $handler): self;

    /**
     * Register a new HEAD route.
     *
     * @param string $uri
     * @param Closure|Handler $handler
     * @return self
     */
    public function head(string $uri, Closure|Handler $handler): self;

    /**
     * Register a new POST route.
     *
     * @param string $uri
     * @param Closure|Handler $handler
     * @return self
     */
    public function post(string $uri, Closure|Handler $handler): self;

    /**
     * Register a new PUT route.
     *
     * @param string $uri
     * @param Closure|Handler $handler
     * @return self
     */
    public function put(string $uri, Closure|Handler $handler): self;

    /**
     * Register a new PATCH route.
     *
     * @param string $uri
     * @param Closure|Handler $handler
     * @return self
     */
    public function patch(string $uri, Closure|Handler $handler): self;

    /**
     * Register a new DELETE route.
     *
     * @param string $uri
     * @param Closure|Handler $handler
     * @return self
     */
    public function delete(string $uri, Closure|Handler $handler): self;
}
