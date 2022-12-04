<?php

declare(strict_types=1);

namespace OzeFramework\Container;

use ArgumentCountError;
use Closure;
use Exception;
use ReflectionClass;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionUnionType;
use OzeFramework\Exceptions\Container\ContainerException;
use OzeFramework\Exceptions\Container\NotFoundException;
use OzeFramework\Interfaces\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    /**
     * The class entries.
     * 
     * @var array<string, Closure|string> $entries
     */
    private array $entries = [];

    /**
     * {@inheritdoc}
     */
    final public function get(string $id): mixed
    {
        try {
            return $this->resolve($id);
        } catch (Exception $e) {
            if ($e instanceof ContainerException) {
                http_response_code(500);
                throw $e;
            }

            http_response_code(500);
            throw new NotFoundException("No entry was found for {$id} identifier.");
        } catch (ArgumentCountError $e) {
            throw new ContainerException($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(), $e->getCode());
        }
    }

    /**
     * {@inheritdoc}
     */
    final public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    /**
     * {@inheritdoc}
     */
    final public function bind(string $id, Closure|string $concrete): void
    {
        $this->entries[$id] = $concrete;
    }

    /**
     * Get all method dependencies.
     * 
     * @param object $object
     * @param string $method
     * 
     * @return array
     */
    final public function getMethodDependencies(object $object, string $method): array
    {
        $reflectionMethod = new ReflectionMethod($object, $method);

        if ($reflectionMethod->getNumberOfParameters() === 0) {
            return [];
        }

        $params       = $reflectionMethod->getParameters();
        $dependencies = [];

        $this->resolveDependencies($params, $dependencies);

        return $dependencies;
    }

    /**
     * Recursively resolve an entry and its dependencies.
     * 
     * @param string $id Identifier of the entry to look for.
     * 
     * @return object
     */
    private function resolve(string $id): object
    {
        $reflectionClass = new ReflectionClass($id);

        if (!$reflectionClass->isInstantiable()) {
            if ($this->has($id)) {
                $entry = $this->entries[$id];

                if ($entry instanceof Closure) {
                    return $entry($this);
                }

                return $this->resolve($entry);
            }

            http_response_code(500);
            throw new ContainerException("Class {$id} is not instantiable");
        }

        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor) || $constructor->getNumberOfParameters() === 0) {
            return $reflectionClass->newInstance();
        }

        $params       = $constructor->getParameters();
        $dependencies = [];

        $this->resolveDependencies($params, $dependencies);

        return $reflectionClass->newInstance(...$dependencies);
    }

    /**
     * Resolve the dependencies.
     * 
     * @param array<int, ReflectionParameter> $params
     * @param array $dependencies
     * 
     * @return void
     */
    private function resolveDependencies(array $params, array &$dependencies): void
    {
        foreach ($params as $param) {
            if (!$param->hasType()) {
                continue;
            }

            $type = $param->getType();

            if ($type instanceof ReflectionUnionType || $type instanceof ReflectionIntersectionType) {
                throw new ContainerException("Union type and Intersection type is not supported yet.");
            }

            if ($type->isBuiltin()) {
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[$param->name] = $param->getDefaultValue();
                }

                if (!$param->isDefaultValueAvailable() && $param->allowsNull()) {
                    $dependencies[$param->name] = null;
                }

                continue;
            }

            $dependencies[$param->name] = $this->resolve($type->getName());
        }
    }
}
