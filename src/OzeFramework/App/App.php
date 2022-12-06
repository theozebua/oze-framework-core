<?php

declare(strict_types=1);

namespace OzeFramework\App;

use Exception;
use OzeFramework\Container\Container;
use OzeFramework\Env\Environment;
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
     * The environment variable class.
     * 
     * @var Environment $env
     */
    private static Environment $env;

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
    final public function setup(): void
    {
        static::$env = new Environment();

        foreach ($_ENV as $key => $value) {
            static::$env->$key = $value;
        }
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

    /**
     * Get environment variable by the given key.
     * 
     * @param string $key
     * 
     * @return mixed
     */
    final public static function env(string $key): mixed
    {
        return static::$env->get($key);
    }
}
