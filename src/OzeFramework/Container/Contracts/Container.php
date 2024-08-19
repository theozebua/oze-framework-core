<?php

declare(strict_types=1);

namespace OzeFramework\Container\Contracts;

use Closure;
use OzeFramework\Container\Exceptions\BindingResolutionException;
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
     * @return void
     */
    public function singleton(string $abstract, null|Closure|string $concrete = null): void;

    /**
     * Resolve an instance of the given type.
     *
     * @template T
     *
     * @param class-string<T> $abstract
     * @param array<string, mixed> $parameters
     * @throws BindingResolutionException
     * @return T
     */
    public function make(string $abstract, array $parameters = []): mixed;
}
