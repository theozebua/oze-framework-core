<?php

declare(strict_types=1);

namespace OzeFramework\Http\Attributes;

use OzeFramework\Http\Enums\HttpMethod;

class Get extends Route
{
    public function __construct(string $uri)
    {
        parent::__construct(HttpMethod::GET, $uri);
    }
}
