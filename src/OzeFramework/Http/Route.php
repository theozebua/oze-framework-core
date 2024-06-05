<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use Closure;
use OzeFramework\Http\Enums\HttpMethod;

final readonly class Route
{
    public function __construct(public HttpMethod $httpMethod, public string $uri, public Closure|Handler $handler)
    {
        //
    }
}
