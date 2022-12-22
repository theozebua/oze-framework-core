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
use OzeFramework\Http\Response;

final class RouteRegistrar implements RouteRegistrarInterface
{
    /** 
     * The DI Container.
     * 
     * @var Container $container
     */
    private Container $container;

    /**
     * The Response class.
     * 
     * @var Response $response
     */
    private Response $response;

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
     * Create a RouteRegistrar instance.
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

            $this->response->statusCode(Response::METHOD_NOT_ALLOWED);
            throw new InvalidArgumentException("Method {$requestMethod} is not allowed. Supported method are {$requestMethods}");
        }

        $route  = parse_url($requestUri, PHP_URL_PATH);
        $action = $this->routes[$requestMethod][$route] ?? null;

        if (!$action) {
            $this->response->statusCode(Response::NOT_FOUND);
            throw new RouteNotFoundException("Route {$route} with {$requestMethod} method is not found");
        }

        if (!$action instanceof Closure && !is_array($action)) {
            $this->response->statusCode(Response::INTERNAL_SERVER_ERROR);
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
     * Set Container Instance
     * 
     * @param Container $container
     * 
     * @return void
     */
    final public function setContainer(Container $container)
    {
        $this->container = $container;
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
            $this->response->statusCode(Response::INTERNAL_SERVER_ERROR);
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
            $this->response->statusCode(Response::INTERNAL_SERVER_ERROR);
            throw new BadMethodCallException("Method {$method} is not found in {$class}");
        }

        return call_user_func_array([$object, $method], $this->container->getMethodDependencies($object, $method));
    }
}
