<?php

declare(strict_types=1);

namespace OzeFramework\Routing;

use Closure;
use OzeFramework\Container\Container;
use OzeFramework\Http\Contracts\Controller;
use OzeFramework\Http\Contracts\RouteRegistrar as RouteRegistrarContract;
use OzeFramework\Http\Enums\HttpMethod;
use OzeFramework\Http\Handler;
use OzeFramework\Http\Router;
use ReflectionClass;

class RouteRegistrar implements RouteRegistrarContract
{
    /** @var array<string, array<string, string>> $routes */
    protected array $routes = [];

    public function __construct(protected ?Container $container = null)
    {
        $this->container ??= Container::getInstance();
    }

    /**
     * {@inheritDoc}
     */
    public function registerRoutesFromControllerAttribute(array $controllers): void
    {
        foreach ($controllers as $controller) {
            if (!$controller instanceof Controller) {
                continue;
            }

            $reflection = new ReflectionClass($controller);
            $methods = $reflection->getMethods();

            foreach ($methods as $method) {
                $attributes = $method->getAttributes(Router::class);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();

                    $this->registerRoute($route->method, $route->uri, new Handler($controller::class, $method->getName()));
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function registerRoute(HttpMethod $httpMethod, string $uri, Closure|Handler $handler): self
    {
        $this->routes[$httpMethod->value][$uri] = $handler;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->routes[HttpMethod::GET->value][$uri] = $handler;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function head(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->routes[HttpMethod::HEAD->value][$uri] = $handler;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function post(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->routes[HttpMethod::POST->value][$uri] = $handler;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function put(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->routes[HttpMethod::PUT->value][$uri] = $handler;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function patch(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->routes[HttpMethod::PATCH->value][$uri] = $handler;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->routes[HttpMethod::DELETE->value][$uri] = $handler;

        return $this;
    }
}
