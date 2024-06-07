<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Routing\Helpers\Controller;

use OzeFramework\Routing\Attributes\Get;
use OzeFramework\Routing\Contracts\Controller;

final class ControllerWithRouteAttribute implements Controller
{
    #[Get(uri: '/controller-with-route-attribute')]
    public function index(): string
    {
        return 'ControllerWithRouteAttribute';
    }
}
