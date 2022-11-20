<?php

declare(strict_types=1);

namespace Theozebua\OzeFramework\Tests\Router;

use PHPUnit\Framework\TestCase;
use Theozebua\OzeFramework\Router\Route;

final class RouteTest extends TestCase
{
    private Route $route;

    final protected function setUp(): void
    {
        $this->route = new Route();
    }

    final public function testIfGetRouteRegisteredSuccessfully(): void
    {
        $closure = fn () => 'Success';

        $this->route->get('/', ['SomeController', 'index']);
        $this->route->get('/something', $closure);

        $excpected = [
            'GET' => [
                '/' => ['SomeController', 'index'],
                '/something' => $closure
            ]
        ];

        $this->assertSame($excpected, $this->route->routeRegistrar->getRoutes());
    }

    final public function testIfPostRouteRegisteredSuccessfully(): void
    {
        $closure = fn () => 'Success';

        $this->route->post('/', ['SomeController', 'index']);
        $this->route->post('/something', $closure);

        $excpected = [
            'POST' => [
                '/' => ['SomeController', 'index'],
                '/something' => $closure
            ]
        ];

        $this->assertSame($excpected, $this->route->routeRegistrar->getRoutes());
    }

    final public function testIfPutRouteRegisteredSuccessfully(): void
    {
        $closure = fn () => 'Success';

        $this->route->put('/', ['SomeController', 'index']);
        $this->route->put('/something', $closure);

        $excpected = [
            'PUT' => [
                '/' => ['SomeController', 'index'],
                '/something' => $closure
            ]
        ];

        $this->assertSame($excpected, $this->route->routeRegistrar->getRoutes());
    }

    final public function testiIfPatchRouteRegisteredSuccessfully(): void
    {
        $closure = fn () => 'Success';

        $this->route->patch('/', ['SomeController', 'index']);
        $this->route->patch('/something', $closure);

        $excpected = [
            'PATCH' => [
                '/' => ['SomeController', 'index'],
                '/something' => $closure
            ]
        ];

        $this->assertSame($excpected, $this->route->routeRegistrar->getRoutes());
    }

    final public function testIfDeleteRouteRegisteredSuccessfully(): void
    {
        $closure = fn () => 'Success';

        $this->route->delete('/', ['SomeController', 'index']);
        $this->route->delete('/something', $closure);

        $excpected = [
            'DELETE' => [
                '/' => ['SomeController', 'index'],
                '/something' => $closure
            ]
        ];

        $this->assertSame($excpected, $this->route->routeRegistrar->getRoutes());
    }
}
