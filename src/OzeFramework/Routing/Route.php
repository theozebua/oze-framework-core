<?php

declare(strict_types=1);

namespace OzeFramework\Routing;

use Closure;
use OzeFramework\Routing\Enums\HttpMethod;

final readonly class Route
{
    /**
     * Create a new route instance.
     *
     * @return void
     */
    public function __construct(public HttpMethod $httpMethod, public string $uri, public Closure|Handler $handler)
    {
        //
    }
}
