<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http\Helpers\Controller;

use OzeFramework\Http\Contracts\Controller;

final class RegularController implements Controller
{
    public function index(): string
    {
        return 'Regular Controller';
    }
}
