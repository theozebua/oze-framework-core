<?php

declare(strict_types=1);

namespace OzeFramework\App;

use OzeFramework\App\Contracts\App as AppContract;
use OzeFramework\Container\Container;
use OzeFramework\Env\Loader;

class App extends Container implements AppContract
{
    protected string $appPath;

    protected string $basePath;

    protected string $configPath;

    public function __construct(string $basePath)
    {
        $this->appPath = $basePath.'/app';
        $this->basePath = $basePath;
        $this->configPath = $basePath.'/config';

        (new Loader($this->basePath))->load();
    }

    public function setAppPath(string $path = 'app'): void
    {
        $this->appPath = $path;
    }

    public function setBasePath(string $path = ''): void
    {
        $this->basePath = $path;
    }

    public function setConfigPath(string $path = 'config'): void
    {
        $this->configPath = $path;
    }

    public function run(): void
    {
        //
    }
}
