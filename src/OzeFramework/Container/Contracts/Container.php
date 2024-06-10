<?php

declare(strict_types=1);

namespace OzeFramework\Container\Contracts;

use Closure;
use Psr\Container\ContainerInterface;

interface Container extends ContainerInterface
{
    /**
     * Bind an abstract type to a concrete implementation.
     */
    public function bind(string $abstract, null|Closure|string $concrete = null, bool $singleton = false): void;

    /**
     * Bind an abstract type to a concrete implementation as a singleton.
     */
    public function singleton(string $abstract, null|Closure|string $concrete = null): void;

    /**
     * Resolve an instance of the given type.
     *
     * @template T
     *
     * @param  class-string<T>  $abstract
     * @return T
     */
    public function make(string $abstract, array $parameters = []): mixed;
}
