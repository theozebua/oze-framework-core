<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use OzeFramework\Http\Contracts\Controller;

final readonly class Handler
{
    /**
     * Create a new handler instance.
     *
     * @param class-string<Controller> $controller
     * @param string $method
     * @return void
     */
    public function __construct(public string $controller, public string $method)
    {
        //
    }
}
