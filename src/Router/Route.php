<?php

declare(strict_types=1);

namespace Theozebua\TeecoderFramework\Router;

use Closure;
use Theozebua\TeecoderFramework\Interfaces\Router\RouteInterface;

final class Route implements RouteInterface
{
    /**
     * The RouteRegistrar class.
     * 
     * @var RouteRegistrar $routeRegistrar
     */
    public readonly RouteRegistrar $routeRegistrar;

    /**
     * Create a route instance.
     * 
     * @return void
     */
    final public function __construct()
    {
        $this->routeRegistrar = new RouteRegistrar();
    }

    /**
     * {@inheritdoc}
     */
    final public function get(string $uri, Closure|array $action): void
    {
        $this->routeRegistrar->register('GET', $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    final public function post(string $uri, Closure|array $action): void
    {
        $this->routeRegistrar->register('POST', $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    final public function put(string $uri, Closure|array $action): void
    {
        $this->routeRegistrar->register('PUT', $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    final public function patch(string $uri, Closure|array $action): void
    {
        $this->routeRegistrar->register('PATCH', $uri, $action);
    }

    /**
     * {@inheritdoc}
     */
    final public function delete(string $uri, Closure|array $action): void
    {
        $this->routeRegistrar->register('DELETE', $uri, $action);
    }
}
