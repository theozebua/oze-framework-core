<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use OzeFramework\Http\Enums\HttpMethod;

class Head extends Router
{
    public function __construct(string $uri)
    {
        parent::__construct(HttpMethod::HEAD, $uri);
    }
}
