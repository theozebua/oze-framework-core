<?php

declare(strict_types=1);

namespace OzeFramework\Http\Attributes;

use Attribute;
use OzeFramework\Http\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Get extends Route
{
    /**
     * Create a new GET route attribute instance.
     *
     * @param string $uri
     * @return void
     */
    public function __construct(string $uri)
    {
        parent::__construct(HttpMethod::GET, $uri);
    }
}
