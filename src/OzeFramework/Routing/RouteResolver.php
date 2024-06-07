<?php

declare(strict_types=1);

namespace OzeFramework\Routing;

use OzeFramework\Container\Container;

class RouteResolver
{
    public function __construct(protected ?Container $container = null)
    {
        $this->container ??= Container::getInstance();
    }

    public function resolve()
    {
        //
    }
}
