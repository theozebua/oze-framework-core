<?php

declare(strict_types=1);

namespace OzeFramework\Env;

use Dotenv\Dotenv;

class Loader
{
    public function __construct(protected string $path)
    {
        //
    }

    /**
     * @return array<string, string | null>
     */
    public function load(): array
    {
        return Dotenv::createImmutable($this->path)->load();
    }
}
