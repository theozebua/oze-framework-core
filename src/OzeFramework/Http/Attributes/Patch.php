<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use OzeFramework\Http\Enums\HttpMethod;

class Patch extends Router
{
    public function __construct(string $uri)
    {
        parent::__construct(HttpMethod::PATCH, $uri);
    }
}
