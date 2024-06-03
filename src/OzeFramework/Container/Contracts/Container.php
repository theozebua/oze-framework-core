<?php

declare(strict_types=1);

namespace OzeFramework\Container\Contracts;

use Closure;
use Psr\Container\ContainerInterface;

interface Container extends ContainerInterface
{
    public function bind(string $abstract, null|Closure|string $concrete = null, bool $singleton = false): void;

    public function singleton(string $abstract, null|Closure|string $concrete = null): void;
}
