<?php

declare(strict_types=1);

namespace OzeFramework\App;

use OzeFramework\App\Contracts\App as AppContract;
use OzeFramework\Container\Container;
use OzeFramework\Env\Loader;
use OzeFramework\Routing\Contracts\RouteRegistrar as RouteRegistrarContract;
use OzeFramework\Routing\RouteRegistrar;

class App extends Container implements AppContract
{
    /**
     * The path to the application directory.
     */
    protected string $appPath;

    /**
     * The base path of the application.
     */
    protected string $basePath;

    /**
     * The path to the application config directory.
     */
    protected string $configPath;

    /**
     * Create a new App instance.
     *
     * @return void
     */
    public function __construct(string $basePath)
    {
        $this->appPath = $basePath.'/app';
        $this->basePath = $basePath;
        $this->configPath = $basePath.'/config';

        (new Loader($this->basePath))->load();
    }

    /**
     * {@inheritdoc}
     */
    public function setAppPath(string $path = 'app'): void
    {
        $this->appPath = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function setBasePath(string $path = ''): void
    {
        $this->basePath = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function setConfigPath(string $path = 'config'): void
    {
        $this->configPath = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function run(): void
    {
        $this->singleton(RouteRegistrarContract::class, fn (): RouteRegistrar => new RouteRegistrar($this));
    }
}
