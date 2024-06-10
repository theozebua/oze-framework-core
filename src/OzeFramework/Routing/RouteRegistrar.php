<?php

declare(strict_types=1);

namespace OzeFramework\Routing;

use Closure;
use OzeFramework\Container\Container;
use OzeFramework\Routing\Attributes\Route as RouteAttribute;
use OzeFramework\Routing\Contracts\Controller;
use OzeFramework\Routing\Contracts\RouteRegistrar as RouteRegistrarContract;
use OzeFramework\Routing\Enums\HttpMethod;
use ReflectionAttribute;
use ReflectionClass;

class RouteRegistrar implements RouteRegistrarContract
{
    /** @var Route[] */
    protected array $routes = [];

    /**
     * Create a new RouteRegistrar instance.
     *
     * @return void
     */
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
            $reflection = new ReflectionClass($controller);

            if (! $reflection->implementsInterface(Controller::class)) {
                continue;
            }

            $methods = $reflection->getMethods();

            foreach ($methods as $method) {
                $attribute = $method->getAttributes(RouteAttribute::class, ReflectionAttribute::IS_INSTANCEOF)[0] ?? null;

                if (is_null($attribute)) {
                    continue;
                }

                $route = $attribute->newInstance();

                $this->registerRoute($route->method, $route->uri, new Handler($controller, $method->getName()));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function registerRoute(HttpMethod $httpMethod, string $uri, Closure|Handler $handler): self
    {
        $this->routes[] = new Route($httpMethod, $uri, $handler);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->registerRoute(HttpMethod::GET, $uri, $handler);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function head(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->registerRoute(HttpMethod::HEAD, $uri, $handler);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function post(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->registerRoute(HttpMethod::POST, $uri, $handler);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function put(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->registerRoute(HttpMethod::PUT, $uri, $handler);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function patch(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->registerRoute(HttpMethod::PATCH, $uri, $handler);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $uri, Closure|Handler $handler): RouteRegistrarContract
    {
        $this->registerRoute(HttpMethod::DELETE, $uri, $handler);

        return $this;
    }

    /**
     * Get all registered routes.
     *
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
