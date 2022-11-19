<?php

declare(strict_types=1);

namespace Theozebua\TeecoderFramework\Interfaces\Router;

use Closure;
use InvalidArgumentException;
use Theozebua\TeecoderFramework\Exceptions\Router\RouteNotFoundException;

interface RouteRegistrarInterface
{
    /**
     * Register a route.
     * 
     * @param string $requestMethod
     * @param string $uri
     * @param Closure|array<string, string> $action
     * 
     * @return self
     */
    public function register(string $requestMethod, string $uri, Closure|array $action): self;

    /**
     * Resolve the registered routes.
     * 
     * @param string $requestUri
     * @param string $requestMethod
     * 
     * @throws RouteNotFoundException
     * @throws InvalidArgumentException
     * 
     * @return mixed
     */
    public function resolve(string $requestUri, string $requestMethod): mixed;
}
