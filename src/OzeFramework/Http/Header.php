<?php

declare(strict_types=1);

namespace OzeFramework\Http;

final readonly class Header
{
    public function __construct(public string $key, public array $values)
    {
        //
    }
}
