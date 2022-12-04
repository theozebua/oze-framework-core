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
    final public function __construct(string $rootDir, private Container $container, private Route $route)
    {
        self::$rootDir = $rootDir;
    }

    /**
     * {@inheritdoc}
     */
    final public function run(): void
    {
        try {
            $requestMethod = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
            $this->route->routeRegistrar->setContainer($this->container);
            echo $this->route->routeRegistrar->resolve($_SERVER['REQUEST_URI'], strtoupper($requestMethod));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
