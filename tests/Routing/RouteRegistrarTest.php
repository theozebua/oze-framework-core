<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Routing;

use OzeFramework\Container\Container;
use OzeFramework\Routing\Contracts\RouteRegistrar as RouteRegistrarContract;
use OzeFramework\Routing\Handler;
use OzeFramework\Routing\Route;
use OzeFramework\Routing\RouteRegistrar;
use OzeFramework\Tests\Routing\Helpers\Controller\ControllerWithRouteAttribute;
use OzeFramework\Tests\Routing\Helpers\Controller\RegularController;
use PHPUnit\Framework\TestCase;

use function strtoupper;

final class RouteRegistrarTest extends TestCase
{
    protected Container $container;

    protected RouteRegistrar $routeRegistrar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = Container::getInstance();

        $this->container->singleton(RouteRegistrarContract::class, fn (): RouteRegistrar => new RouteRegistrar($this->container));

        $this->routeRegistrar = $this->container->get(RouteRegistrarContract::class);
    }

    public function testRoutesCanBeRegistered(): void
    {
        $methods = ['get', 'head', 'post', 'put', 'patch', 'delete'];

        foreach ($methods as $method) {
            // Register routes with closure handler
            $this->routeRegistrar->{$method}('/', fn (): string => strtoupper($method) . ' Route');
        }

        // Register routes with controller
        $this->routeRegistrar->get('/regular', new Handler(RegularController::class, 'index'));

        $routes = $this->routeRegistrar->getRoutes();

        $this->assertCount(7, $routes);
        $this->assertContainsOnlyInstancesOf(Route::class, $routes);
    }

    public function testRoutesCanBeRegisteredFromControllerAttribute(): void
    {
        $this->routeRegistrar->registerRoutesFromControllerAttribute([
            ControllerWithRouteAttribute::class,
        ]);

        $routes = $this->routeRegistrar->getRoutes();

        $this->assertCount(8, $routes);
        $this->assertContainsOnlyInstancesOf(Route::class, $routes);
    }
}
