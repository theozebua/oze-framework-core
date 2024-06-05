<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http\Helpers\Controller;

use OzeFramework\Http\Attributes\Get;
use OzeFramework\Http\Enums\HttpMethod;

final class ControllerWithRouteAttribute
{
    #[Get(method: HttpMethod::GET, uri: '/controller-with-route-attribute')]
    public function index(): string
    {
        return 'ControllerWithRouteAttribute';
    }
}
