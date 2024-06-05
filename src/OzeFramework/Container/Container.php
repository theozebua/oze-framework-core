<?php

declare(strict_types=1);

namespace OzeFramework\Container;

use Closure;
use OzeFramework\Container\Contracts\Container as ContainerContract;
use OzeFramework\Container\Exceptions\BindingResolutionException;
use OzeFramework\Container\Exceptions\EntryNotFoundException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;

class Container implements ContainerContract
{
    /** @var Container|null Container instance */
    protected static ?Container $instance = null;

    /** @var array<string, Binding> */
    protected array $bindings = [];

    /** @var array<string, mixed> Singleton instances */
    protected array $instances = [];

    /**
     * Get the singleton instance of the container.
     * 
     * @return static
     */
    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Set the container instance.
     * 
     * @param Container $container
     * @return Container
     */
    public static function setInstance(ContainerContract $container): ContainerContract
    {
        return static::$instance = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new EntryNotFoundException("Entry [{$id}] not found.");
        }

        return $this->resolve($id);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || isset($this->instances[$id]);
    }

    /**
     * {@inheritdoc}
     */
    public function bind(string $abstract, null|Closure|string $concrete = null, bool $singleton = false): void
    {
        $this->bindings[$abstract] = new Binding($concrete ?? $abstract, $singleton);
    }

    /**
     * {@inheritdoc}
     */
    public function singleton(string $abstract, null|Closure|string $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * Resolve a binding from the container.
     *
     * @throws BindingResolutionException
     */
    protected function resolve(string $abstract, array $parameters = []): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $binding = $this->bindings[$abstract];
        $object = $this->build($binding->concrete, $parameters);

        if ($binding->singleton) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    /**
     * Build an instance of the given concrete type.
     *
     * @throws BindingResolutionException
     */
    protected function build(Closure|string $concrete, array $parameters = []): mixed
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        try {
            $reflectionClass = new ReflectionClass($concrete);
        } catch (ReflectionException $e) {
            throw new BindingResolutionException("Target class [{$concrete}] does not exist.", previous: $e);
        }

        if (!$reflectionClass->isInstantiable()) {
            throw new BindingResolutionException("Target class [{$concrete}] is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor)) {
            return $reflectionClass->newInstance();
        }

        $dependencies = $constructor->getParameters();

        return $reflectionClass->newInstanceArgs($this->resolveDependencies($dependencies, $parameters));
    }

    /**
     * Resolve all dependencies for a given set of parameters.
     *
     * @param  ReflectionParameter[]  $dependencies
     * @return array<int, mixed>
     */
    protected function resolveDependencies(array $dependencies, array $parameters = []): array
    {
        return array_map(fn (ReflectionParameter $dependency) => $this->resolveDependency($dependency, $parameters), $dependencies);
    }

    /**
     * Resolve a single dependency.
     *
     * @throws BindingResolutionException
     */
    protected function resolveDependency(ReflectionParameter $dependency, array $parameters = []): mixed
    {
        $type = $dependency->getType();

        if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
            if ($dependency->isDefaultValueAvailable()) {
                return $dependency->getDefaultValue();
            }

            return $this->get($type->getName());
        }

        if (array_key_exists($dependency->name, $parameters)) {
            return $parameters[$dependency->name];
        }

        if ($dependency->isDefaultValueAvailable()) {
            return $dependency->getDefaultValue();
        }

        throw new BindingResolutionException("Cannot resolve dependency [{$dependency->name}]");
    }
}
