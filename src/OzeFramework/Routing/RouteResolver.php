<?php

declare(strict_types=1);

namespace OzeFramework\Routing;

use OzeFramework\Container\Container;

class RouteResolver
{
    /**
     * Create a new route resolver instance.
     *
     * @param Container|null $container
     * @return void
     */
    public function __construct(protected ?Container $container = null)
    {
        $this->container ??= Container::getInstance();
    }

    public function resolve()
    {
        //
    }
}
