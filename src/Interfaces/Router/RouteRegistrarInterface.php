<?php

declare(strict_types=1);

namespace Theozebua\TeecoderFramework\Interfaces\Router;

use Closure;
use Theozebua\TeecoderFramework\Exceptions\Router\RouteNotFoundException;

interface RouteRegistrarInterface
{
    /**
     * Register a route.
     * 
     * @param string $requestMethod
     * @param string $uri
     * @param Closure $action
     * 
     * @return self
     */
    public function register(string $requestMethod, string $uri, Closure $action): self;

    /**
     * Resolve the registered routes.
     * 
     * @param string $requestUri
     * 
     * @throws RouteNotFoundException
     * 
     * @return mixed
     */
    public function resolve(string $requestUri): mixed;
}
