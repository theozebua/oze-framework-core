<?php

declare(strict_types=1);

namespace OzeFramework\Http\Attributes;

use Attribute;
use OzeFramework\Http\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(public readonly HttpMethod $method, public readonly string $uri)
    {
        //
    }
}
