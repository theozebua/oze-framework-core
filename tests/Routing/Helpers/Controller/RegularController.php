<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Routing\Helpers\Controller;

use OzeFramework\Routing\Contracts\Controller;

final class RegularController implements Controller
{
    public function index(): string
    {
        return 'Regular Controller';
    }
}
