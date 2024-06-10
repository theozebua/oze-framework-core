<?php

declare(strict_types=1);

namespace OzeFramework\Routing\Attributes;

use Attribute;
use OzeFramework\Http\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Put extends Route
{
    /**
     * Create a new PUT route attribute instance.
     *
     * @return void
     */
    public function __construct(string $uri)
    {
        parent::__construct(HttpMethod::PUT, $uri);
    }
}
