<?php

declare(strict_types=1);

namespace OzeFramework\App\Contracts;

use OzeFramework\Container\Contracts\Container;

interface App extends Container
{
    public function setAppPath(string $path = 'app'): void;

    public function setBasePath(string $path = ''): void;

    public function setConfigPath(string $path = 'config'): void;

    public function run(): void;
}
