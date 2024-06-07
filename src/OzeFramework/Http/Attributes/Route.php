<?php

declare(strict_types=1);

namespace OzeFramework\Http\Attributes;

use Attribute;
use OzeFramework\Http\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    /**
     * Create a new route attribute instance.
     *
     * @param HttpMethod $method
     * @param string $uri
     * @return void
     */
    public function __construct(public readonly HttpMethod $method, public readonly string $uri)
    {
        //
    }
}
