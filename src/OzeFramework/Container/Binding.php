<?php

declare(strict_types=1);

namespace OzeFramework\Container;

use Closure;

final readonly class Binding
{
    public function __construct(public null|Closure|string $concrete = null, public bool $singleton = false)
    {
        //
    }
}
