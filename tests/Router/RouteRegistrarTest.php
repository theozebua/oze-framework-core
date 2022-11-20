<?php

declare(strict_types=1);

namespace Theozebua\OzeFramework\Tests\Router;

use BadMethodCallException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Theozebua\OzeFramework\Exceptions\Controller\ControllerNotFoundException;
use Theozebua\OzeFramework\Exceptions\Router\RouteNotFoundException;
use Theozebua\OzeFramework\Router\RouteRegistrar;

final class RouteRegistrarTest extends TestCase
{
    private RouteRegistrar $routeRegistrar;

    final protected function setUp(): void
    {
        $this->routeRegistrar = new RouteRegistrar();
    }

    final public function testIfRoutesIsEmptyWhenRouteRegistrarIsInstantiated(): void
    {
        $routes = $this->routeRegistrar->getRoutes();

        $this->assertEmpty($routes);
    }

    final public function testIfControllerRouteRegisteredSuccessfully(): void
    {
        $this->routeRegistrar->register('GET', '/', ['SomeController', 'index']);

        $excpected = [
            'GET' => [
                '/' => [
                    'SomeController',
                    'index'
                ]
            ]
        ];

        $this->assertEquals($excpected, $this->routeRegistrar->getRoutes());
    }

    final public function testIfControllerRouteResolvedSuccessfully(): void
    {
        $someController = new class
        {
            final public function index(): string
            {
                return 'Success';
            }
        };

        $this->routeRegistrar->register('GET', '/', [$someController::class, 'index']);

        $this->assertEquals('Success', $this->routeRegistrar->resolve('/', 'GET'));
    }

    final public function testIfClosureRouteRegisteredSuccessfully(): void
    {
        $this->routeRegistrar->register('GET', '/', function () {
            return;
        });

        $excpected = [
            'GET' => [
                '/' => function () {
                    return;
                }
            ]
        ];

        $this->assertEquals($excpected, $this->routeRegistrar->getRoutes());
    }

    final public function testIfClosureRouteResolvedSuccessfully(): void
    {
        $this->routeRegistrar->register('GET', '/', fn () => 'Success');

        $this->assertEquals('Success', $this->routeRegistrar->resolve('/', 'GET'));
    }

    final public function testIfItThrowsInvalidArgumentExceptionWhenRequestMethodIsInvalid(): void
    {
        $this->routeRegistrar->register('GET', '/', ['SomeController', 'index']);

        $this->expectException(InvalidArgumentException::class);

        $this->routeRegistrar->resolve('/', 'INVALID_REQUEST_METHOD');
    }

    final public function testIfItThrowsRouteNotFoundExceptionWhenRouteIsNotFound(): void
    {
        $this->expectException(RouteNotFoundException::class);

        $this->routeRegistrar->resolve('/', 'GET');
    }

    final public function testIfItThrowsControllerNotFoundExceptionWhenControllerClassIsNotFound(): void
    {
        $this->routeRegistrar->register('GET', '/', ['SomeController', 'index']);

        $this->expectException(ControllerNotFoundException::class);

        $this->routeRegistrar->resolve('/', 'GET');
    }

    final public function testIfItThrowsBadMethodCallExceptionWhenMethodIsNotFound(): void
    {
        $class = new class
        {
            final public function something(): void
            {
                return;
            }
        };

        $this->routeRegistrar->register('GET', '/', [$class::class, 'index']);

        $this->expectException(BadMethodCallException::class);

        $this->routeRegistrar->resolve('/', 'GET');
    }
}
