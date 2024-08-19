<?php

declare(strict_types=1);

namespace OzeFramework\Http;

final readonly class Header
{
    /**
     * Create a new header instance.
     *
     * @param string $key
     * @param array $values
     * @return void
     */
    public function __construct(public string $key, public array $values)
    {
        //
    }
}
