<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\Container;

use Closure;
use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    /**
     * Register a binding with the container.
     * 
     * @param string $id Identifier of the entry to look for.
     * @param Closure $concrete
     * 
     * @return void
     */
    public function bind(string $id, Closure $concrete): void;
}
