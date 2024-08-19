<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Container;

use OzeFramework\Container\Container;
use OzeFramework\Container\Exceptions\BindingResolutionException;
use OzeFramework\Container\Exceptions\CircularDependencyException;
use OzeFramework\Container\Exceptions\EntryNotFoundException;
use OzeFramework\Tests\Container\Helpers\Classes\AbstractClass;
use OzeFramework\Tests\Container\Helpers\Classes\ClassThatHasCircularDependencyFirst;
use OzeFramework\Tests\Container\Helpers\Classes\ClassThatHasCircularDependencySecond;
use OzeFramework\Tests\Container\Helpers\Classes\ClassThatHasDependencies;
use OzeFramework\Tests\Container\Helpers\Classes\RegularClass;
use OzeFramework\Tests\Container\Helpers\Interfaces\RegularInterface;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = Container::getInstance();
    }

    public function testContainerCanRegisterRegularBinding(): void
    {
        $firstRegularClass = $this->container->make(RegularClass::class);
        $secondRegularClass = $this->container->make(RegularClass::class);

        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertNotSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanRegisterClosureBinding(): void
    {
        $this->container->bind(RegularClass::class, fn (): RegularClass => new RegularClass());

        $firstRegularClass = $this->container->make(RegularClass::class);
        $secondRegularClass = $this->container->make(RegularClass::class);

        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertNotSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanRegisterSingletonBinding(): void
    {
        $this->container->singleton(RegularClass::class);

        $firstRegularClass = $this->container->make(RegularClass::class);
        $secondRegularClass = $this->container->make(RegularClass::class);

        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanRegisterSingletonClosureBinding(): void
    {
        $this->container->singleton(RegularClass::class, fn (): RegularClass => new RegularClass());

        $firstRegularClass = $this->container->make(RegularClass::class);
        $secondRegularClass = $this->container->make(RegularClass::class);

        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanRegisterBindingThatHasDependencies(): void
    {
        $this->container->bind(ClassThatHasDependencies::class, fn (): ClassThatHasDependencies => new ClassThatHasDependencies(new RegularClass()));

        $firstClassThatHasDependencies = $this->container->make(ClassThatHasDependencies::class);
        $secondClassThatHasDependencies = $this->container->make(ClassThatHasDependencies::class);

        $this->assertInstanceOf(ClassThatHasDependencies::class, $firstClassThatHasDependencies);
        $this->assertInstanceOf(ClassThatHasDependencies::class, $secondClassThatHasDependencies);
        $this->assertNotSame($firstClassThatHasDependencies, $secondClassThatHasDependencies);
        $this->assertObjectHasProperty('regularClass', $firstClassThatHasDependencies);
        $this->assertObjectHasProperty('regularClass', $secondClassThatHasDependencies);
        $this->assertNotSame($firstClassThatHasDependencies->regularClass, $secondClassThatHasDependencies->regularClass);
    }

    public function testContainerCanRegisterSingletonBindingThatHasDependencies(): void
    {
        $this->container->singleton(ClassThatHasDependencies::class, fn (): ClassThatHasDependencies => new ClassThatHasDependencies(new RegularClass()));

        $firstClassThatHasDependencies = $this->container->make(ClassThatHasDependencies::class);
        $secondClassThatHasDependencies = $this->container->make(ClassThatHasDependencies::class);

        $this->assertInstanceOf(ClassThatHasDependencies::class, $firstClassThatHasDependencies);
        $this->assertInstanceOf(ClassThatHasDependencies::class, $secondClassThatHasDependencies);
        $this->assertSame($firstClassThatHasDependencies, $secondClassThatHasDependencies);
        $this->assertObjectHasProperty('regularClass', $firstClassThatHasDependencies);
        $this->assertObjectHasProperty('regularClass', $secondClassThatHasDependencies);
        $this->assertSame($firstClassThatHasDependencies->regularClass, $secondClassThatHasDependencies->regularClass);
    }

    public function testContainerCanRegisterInterfaceBinding(): void
    {
        $this->container->bind(RegularInterface::class, RegularClass::class);

        $firstRegularClass = $this->container->make(RegularInterface::class);
        $secondRegularClass = $this->container->make(RegularInterface::class);

        $this->assertInstanceOf(RegularInterface::class, $firstRegularClass);
        $this->assertInstanceOf(RegularInterface::class, $secondRegularClass);
        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertNotSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanRegisterInterfaceBindingWithClosure(): void
    {
        $this->container->bind(RegularInterface::class, fn (): RegularClass => new RegularClass());

        $firstRegularClass = $this->container->make(RegularInterface::class);
        $secondRegularClass = $this->container->make(RegularInterface::class);

        $this->assertInstanceOf(RegularInterface::class, $firstRegularClass);
        $this->assertInstanceOf(RegularInterface::class, $secondRegularClass);
        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertNotSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanRegisterInterfaceSingletonBinding(): void
    {
        $this->container->singleton(RegularInterface::class, RegularClass::class);

        $firstRegularClass = $this->container->make(RegularInterface::class);
        $secondRegularClass = $this->container->make(RegularInterface::class);

        $this->assertInstanceOf(RegularInterface::class, $firstRegularClass);
        $this->assertInstanceOf(RegularInterface::class, $secondRegularClass);
        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanRegisterInterfaceSingletonBindingWithClosure(): void
    {
        $this->container->singleton(RegularInterface::class, fn (): RegularClass => new RegularClass());

        $firstRegularClass = $this->container->make(RegularInterface::class);
        $secondRegularClass = $this->container->make(RegularInterface::class);

        $this->assertInstanceOf(RegularInterface::class, $firstRegularClass);
        $this->assertInstanceOf(RegularInterface::class, $secondRegularClass);
        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanThrowExceptionIfEntryIsNotFound(): void
    {
        $this->expectException(EntryNotFoundException::class);

        $this->container->get('not-found');
    }

    public function testContainerCanThrowExceptionIfTargetClassDoesNotExist(): void
    {
        $this->expectException(BindingResolutionException::class);

        $this->container->make('not-found');
    }

    public function testContainerCanThrowExceptionIfTargetClassIsNotInstantiable(): void
    {
        $this->expectException(BindingResolutionException::class);

        $this->container->make(AbstractClass::class);
    }

    public function testContainerCanThrowExceptionIfThereIsCircularDependency(): void
    {
        $this->expectException(CircularDependencyException::class);

        $this->container->bind(ClassThatHasCircularDependencyFirst::class, function (Container $container): ClassThatHasCircularDependencyFirst {
            return new ClassThatHasCircularDependencyFirst($container->make(ClassThatHasCircularDependencySecond::class));
        });

        $this->container->make(ClassThatHasCircularDependencyFirst::class);
    }
}
