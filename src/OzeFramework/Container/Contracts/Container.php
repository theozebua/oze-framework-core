<?php

declare(strict_types=1);

namespace OzeFramework\Container\Contracts;

use Closure;
use Psr\Container\ContainerInterface;

interface Container extends ContainerInterface
{
    /**
     * Bind an abstract type to a concrete implementation.
     *
     * @param string $abstract
     * @param null|Closure|string $concrete
     * @param bool $singleton
     * @return void
     */
    public function bind(string $abstract, null|Closure|string $concrete = null, bool $singleton = false): void;

    /**
     * Bind an abstract type to a concrete implementation as a singleton.
     *
     * @param string $abstract
     * @param null|Closure|string $concrete
     */
    public function singleton(string $abstract, null|Closure|string $concrete = null): void;
}
