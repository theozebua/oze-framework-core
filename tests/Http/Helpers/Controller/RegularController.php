<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http\Routing\Helpers\Controller;

use OzeFramework\Http\Contracts\Controller;
use OzeFramework\Http\Get;

class RegularController implements Controller
{
    #[Get('/regular')]
    public function index(): string
    {
        return 'Regular Controller';
    }
}
