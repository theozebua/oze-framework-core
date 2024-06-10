<?php

declare(strict_types=1);

namespace OzeFramework\Routing\Attributes;

use Attribute;
use OzeFramework\Routing\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Patch extends Route
{
    /**
     * Create a new PATCH route attribute instance.
     *
     * @return void
     */
    public function __construct(string $uri)
    {
        parent::__construct(HttpMethod::PATCH, $uri);
    }
}
