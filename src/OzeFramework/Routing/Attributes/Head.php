<?php

declare(strict_types=1);

namespace OzeFramework\Routing\Attributes;

use Attribute;
use OzeFramework\Routing\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Head extends Route
{
    /**
     * Create a new HEAD route attribute instance.
     *
     * @param string $uri
     * @return void
     */
    public function __construct(string $uri)
    {
        parent::__construct(HttpMethod::HEAD, $uri);
    }
}
