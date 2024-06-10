<?php

declare(strict_types=1);

namespace OzeFramework\Routing\Attributes;

use Attribute;
use OzeFramework\Routing\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    /**
     * Create a new route attribute instance.
     *
     * @return void
     */
    public function __construct(public readonly HttpMethod $method, public readonly string $uri)
    {
        //
    }
}
