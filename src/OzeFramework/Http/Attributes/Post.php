<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use OzeFramework\Http\Enums\HttpMethod;

class Post extends Router
{
    public function __construct(string $uri)
    {
        parent::__construct(HttpMethod::POST, $uri);
    }
}
