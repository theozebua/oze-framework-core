<?php

declare(strict_types=1);

namespace OzeFramework\Router;

use BadMethodCallException;
use Closure;
use InvalidArgumentException;
use OzeFramework\Container\Container;
use OzeFramework\Exceptions\Controller\ControllerNotFoundException;
use OzeFramework\Exceptions\Router\RouteNotFoundException;
use OzeFramework\Interfaces\Router\RouteRegistrarInterface;

final class RouteRegistrar implements RouteRegistrarInterface
{
    /**
     * The DI Container.
     * 
     * @var Container $container
     */
    private Container $container;

    /**
     * Registered routes.
     * 
     * @var array<string, array<string, Closure|array<int, string>>> $routes
     */
    private array $routes = [];

    /**
     * Supported request methods.
     * 
     * @var array<int, string> $requestMethods
     */
    private array $requestMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * Create RouteRegistrar instance.
     * 
     * @return void
     */
    final public function __construct()
    {
        $this->container = new Container();
    }

    /**
     * {@inheritdoc}
     */
    final public function register(string $requestMethod, string $uri, Closure|array $action): self
    {
        $this->routes[$requestMethod][$uri] = $action;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function resolve(string $requestUri, string $requestMethod): mixed
    {
        if (!in_array($requestMethod, $this->requestMethods)) {
            $requestMethods = implode(', ', $this->requestMethods);

            http_response_code(400);
            throw new InvalidArgumentException("Method {$requestMethod} is not allowed. Supported method are {$requestMethods}");
        }

        $route  = parse_url($requestUri, PHP_URL_PATH);
        $action = $this->routes[$requestMethod][$route] ?? null;

        $this->ignoreRoute($route, '/favicon.ico');

        if (!$action) {
            http_response_code(404);
            throw new RouteNotFoundException("Route {$route} with {$requestMethod} method is not found");
        }

        if (!$action instanceof Closure && !is_array($action)) {
            http_response_code(500);
            throw new InvalidArgumentException("Action must be an instance of \\Closure or array of controller and method");
        }

        if ($action instanceof Closure) {
            return call_user_func($action);
        }

        return $this->resolveController($action);
    }

    /**
     * Get all registered routes.
     * 
     * @return array<string, array<string, Closure|array<int, string>>>
     */
    final public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Create an instance of a controller class.
     * 
     * @param array<string, string> $action
     * 
     * @throws ControllerNotFoundException
     * 
     * @return mixed
     */
    private function resolveController(array $action): mixed
    {
        [$class, $method] = $action;

        if (!class_exists($class)) {
            http_response_code(500);
            throw new ControllerNotFoundException("{$class} is not found");
        }

        $object = $this->container->get($class);

        return $this->resolveMethod($object, $class, $method);
    }

    /**
     * Call a controller's method.
     * 
     * @param object $object
     * @param string $class
     * @param string $method
     * 
     * @throws BadMethodCallException
     * 
     * @return mixed
     */
    private function resolveMethod(object $object, string $class, string $method): mixed
    {
        if (!method_exists($class, $method)) {
            http_response_code(500);
            throw new BadMethodCallException("Method {$method} is not found in {$class}");
        }

        return call_user_func_array([$object, $method], $this->container->getMethodDependencies($object, $method));
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
