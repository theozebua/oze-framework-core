<?php

declare(strict_types=1);

namespace Theozebua\TeecoderFramework\Router;

use Closure;
use Theozebua\TeecoderFramework\Exceptions\Router\RouteNotFoundException;
use Theozebua\TeecoderFramework\Interfaces\Router\RouteRegistrarInterface;

final class RouteRegistrar implements RouteRegistrarInterface
{
    /**
     * Registered routes.
     * 
     * @var array<string, array<string, Closure>> $routes
     */
    private array $routes = [];

    /**
     * {@inheritdoc}
     */
    final public function register(string $requestMethod, string $uri, Closure $action): self
    {
        $this->routes[$requestMethod][$uri] = $action;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function resolve(string $requestUri): mixed
    {
        $route  = parse_url($requestUri)['path'];
        $action = $this->routes['GET'][$route] ?? null;

        $this->ignoreRoute($route, '/favicon.ico');

        if (!$action) {
            throw new RouteNotFoundException("Route {$route} is not found.");
        }

        return call_user_func($action);
    }

    /**
     * Ignore a route.
     * 
     * @param string $uri
     * @param string $ignore
     * 
     * @return void
     */
    private function ignoreRoute(string $uri, string $ignore): void
    {
        if ($uri === $ignore) {
            exit;
        }
    }
}
