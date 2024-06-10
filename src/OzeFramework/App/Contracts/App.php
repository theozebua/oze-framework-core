<?php

declare(strict_types=1);

namespace OzeFramework\App\Contracts;

use OzeFramework\Container\Contracts\Container;

interface App extends Container
{
    /**
     * Set the app path for the application.
     */
    public function setAppPath(string $path = 'app'): void;

    /**
     * Set the base path for the application.
     */
    public function setBasePath(string $path = ''): void;

    /**
     * Set the config path for the application.
     */
    public function setConfigPath(string $path = 'config'): void;

    /**
     * Run the application.
     */
    public function run(): void;
}
