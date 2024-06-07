<?php

declare(strict_types=1);

namespace OzeFramework\Env;

use Dotenv\Dotenv;

class Loader
{
    /**
     * Create a new environment loader instance.
     *
     * @param string $path
     * @return void
     */
    public function __construct(protected string $path)
    {
        //
    }

    /**
     * Load the environment file.
     *
     * @return array<string, string | null>
     */
    public function load(): array
    {
        return Dotenv::createImmutable($this->path)->load();
    }
}
