<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Container;

use OzeFramework\Container\Container;
use OzeFramework\Tests\Container\Helpers\Classes\ClassThatHasDependencies;
use OzeFramework\Tests\Container\Helpers\Classes\RegularClass;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new Container();
    }

    public function testContainerCanRegisterRegularBinding(): void
    {
        $this->container->bind(RegularClass::class);

        $firstRegularClass = $this->container->get(RegularClass::class);
        $secondRegularClass = $this->container->get(RegularClass::class);

        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertNotSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanRegisterClosureBinding(): void
    {
        $this->container->bind(RegularClass::class, function (): RegularClass {
            return new RegularClass();
        });

        $firstRegularClass = $this->container->get(RegularClass::class);
        $secondRegularClass = $this->container->get(RegularClass::class);

        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertNotSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanRegisterSingletonBinding(): void
    {
        $this->container->singleton(RegularClass::class);

        $firstRegularClass = $this->container->get(RegularClass::class);
        $secondRegularClass = $this->container->get(RegularClass::class);

        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanRegisterSingletonClosureBinding(): void
    {
        $this->container->singleton(RegularClass::class, function (): RegularClass {
            return new RegularClass();
        });

        $firstRegularClass = $this->container->get(RegularClass::class);
        $secondRegularClass = $this->container->get(RegularClass::class);

        $this->assertInstanceOf(RegularClass::class, $firstRegularClass);
        $this->assertInstanceOf(RegularClass::class, $secondRegularClass);
        $this->assertSame($firstRegularClass, $secondRegularClass);
    }

    public function testContainerCanRegisterBindingThatHasDependencies(): void
    {
        $this->container->bind(ClassThatHasDependencies::class, fn (): ClassThatHasDependencies => new ClassThatHasDependencies(new RegularClass()));

        $firstClassThatHasDependencies = $this->container->get(ClassThatHasDependencies::class);
        $secondClassThatHasDependencies = $this->container->get(ClassThatHasDependencies::class);

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

        $firstClassThatHasDependencies = $this->container->get(ClassThatHasDependencies::class);
        $secondClassThatHasDependencies = $this->container->get(ClassThatHasDependencies::class);

        $this->assertInstanceOf(ClassThatHasDependencies::class, $firstClassThatHasDependencies);
        $this->assertInstanceOf(ClassThatHasDependencies::class, $secondClassThatHasDependencies);
        $this->assertSame($firstClassThatHasDependencies, $secondClassThatHasDependencies);
        $this->assertObjectHasProperty('regularClass', $firstClassThatHasDependencies);
        $this->assertObjectHasProperty('regularClass', $secondClassThatHasDependencies);
        $this->assertSame($firstClassThatHasDependencies->regularClass, $secondClassThatHasDependencies->regularClass);
    }
}
