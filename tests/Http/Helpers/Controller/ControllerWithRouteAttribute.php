<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http\Helpers\Controller;

use OzeFramework\Http\Attributes\Get;
use OzeFramework\Http\Contracts\Controller;

final class ControllerWithRouteAttribute implements Controller
{
    #[Get(uri: '/controller-with-route-attribute')]
    public function index(): string
    {
        return 'ControllerWithRouteAttribute';
    }
}
