<?php

declare(strict_types=1);

namespace OzeFramework\App;

use Exception;
use OzeFramework\Container\Container;
use OzeFramework\Interfaces\App\AppInterface;
use OzeFramework\Router\Route;

class App implements AppInterface
{
    /**
     * The App class.
     * 
     * @var App $app
     */
    public static App $app;

    /**
     * The DI Container class.
     * 
     * @var Container $container
     */
    public Container $container;

    /**
     * The Route class.
     * 
     * @var Route $route
     */
    public Route $route;

    /**
     * The root path.
     * 
     * @var string $rootDir
     */
    public static string $rootDir;

    /**
     * Create the application.
     * 
     * @param string $rootDir
     * 
     * @return void
     */
    final public function __construct(string $rootDir)
    {
        self::$app       = $this;
        self::$rootDir   = $rootDir;
        $this->container = new Container();
        $this->route     = new Route($this->container);
    }

    /**
     * {@inheritdoc}
     */
    final public function run(): void
    {
        try {
            $requestMethod = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

            echo $this->route->routeRegistrar->resolve($_SERVER['REQUEST_URI'], strtoupper($requestMethod));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
