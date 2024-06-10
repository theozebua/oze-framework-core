<?php

declare(strict_types=1);

namespace OzeFramework\Routing\Contracts;

use Closure;
use OzeFramework\Routing\Enums\HttpMethod;
use OzeFramework\Routing\Handler;

interface RouteRegistrar
{
    /**
     * Register a new route.
     */
    public function registerRoute(HttpMethod $httpMethod, string $uri, Closure|Handler $handler): self;

    /**
     * Register routes from controller attributes.
     *
     * @param  class-string<Controller>[]  $controllers
     */
    public function registerRoutesFromControllerAttribute(array $controllers): void;

    /**
     * Register a new GET route.
     */
    public function get(string $uri, Closure|Handler $handler): self;

    /**
     * Register a new HEAD route.
     */
    public function head(string $uri, Closure|Handler $handler): self;

    /**
     * Register a new POST route.
     */
    public function post(string $uri, Closure|Handler $handler): self;

    /**
     * Register a new PUT route.
     */
    public function put(string $uri, Closure|Handler $handler): self;

    /**
     * Register a new PATCH route.
     */
    public function patch(string $uri, Closure|Handler $handler): self;

    /**
     * Register a new DELETE route.
     */
    public function delete(string $uri, Closure|Handler $handler): self;
}
