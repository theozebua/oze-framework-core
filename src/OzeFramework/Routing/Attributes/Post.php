<?php

declare(strict_types=1);

namespace OzeFramework\Routing\Attributes;

use Attribute;
use OzeFramework\Http\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class Post extends Route
{
    /**
     * Create a new POST route attribute instance.
     *
     * @param string $uri
     * @return void
     */
    public function __construct(string $uri)
    {
        parent::__construct(HttpMethod::POST, $uri);
    }
}
