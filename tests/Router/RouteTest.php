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
        $this->route->get('/', ['SomeController', 'index']);
        $this->route->get('/something', function () {
            return;
        });

        $excpected = [
            'GET' => [
                '/' => [
                    'SomeController',
                    'index'
                ],
                '/something' => function () {
                    return;
                }
            ]
        ];

        $this->assertEquals($excpected, $this->route->routeRegistrar->getRoutes());
    }

    final public function testIfPostRouteRegisteredSuccessfully(): void
    {
        $this->route->post('/', ['SomeController', 'store']);
        $this->route->post('/something', function () {
            return;
        });

        $excpected = [
            'POST' => [
                '/' => [
                    'SomeController',
                    'store'
                ],
                '/something' => function () {
                    return;
                }
            ]
        ];

        $this->assertEquals($excpected, $this->route->routeRegistrar->getRoutes());
    }

    final public function testIfPutRouteRegisteredSuccessfully(): void
    {
        $this->route->put('/', ['SomeController', 'update']);
        $this->route->put('/something', function () {
            return;
        });

        $excpected = [
            'PUT' => [
                '/' => [
                    'SomeController',
                    'update'
                ],
                '/something' => function () {
                    return;
                }
            ]
        ];

        $this->assertEquals($excpected, $this->route->routeRegistrar->getRoutes());
    }

    final public function testiIfPatchRouteRegisteredSuccessfully(): void
    {
        $this->route->patch('/', ['SomeController', 'update']);
        $this->route->patch('/something', function () {
            return;
        });

        $excpected = [
            'PATCH' => [
                '/' => [
                    'SomeController',
                    'update'
                ],
                '/something' => function () {
                    return;
                }
            ]
        ];

        $this->assertEquals($excpected, $this->route->routeRegistrar->getRoutes());
    }

    final public function testIfDeleteRouteRegisteredSuccessfully(): void
    {
        $this->route->delete('/', ['SomeController', 'destroy']);
        $this->route->delete('/something', function () {
            return;
        });

        $excpected = [
            'DELETE' => [
                '/' => [
                    'SomeController',
                    'destroy'
                ],
                '/something' => function () {
                    return;
                }
            ]
        ];

        $this->assertEquals($excpected, $this->route->routeRegistrar->getRoutes());
    }
}
