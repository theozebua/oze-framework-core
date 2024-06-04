<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use Attribute;
use OzeFramework\Http\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Router
{
    public function __construct(public readonly HttpMethod $method, public readonly string $uri)
    {
        //
    }
}
