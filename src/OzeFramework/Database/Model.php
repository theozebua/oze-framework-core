<?php

declare(strict_types=1);

namespace OzeFramework\Database;

use OzeFramework\Database\Query\Builder;
use OzeFramework\Interfaces\Database\ModelInterface;

abstract class Model implements ModelInterface
{
    /**
     * The Builder class.
     * 
     * @var Builder $builder
     */
    private static Builder $builder;

    /**
     * Table name.
     * 
     * @var ?string $table
     */
    protected ?string $table = null;

    /**
     * {@inheritdoc}
     */
    final public static function all(array|string $columns = ['*']): array
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        return static::getQueryBuilder()->select(static::getQualifiedColumns($columns))->get();
    }

    /**
     * {@inheritdoc}
     */
    final public static function first(array|string $columns = ['*']): object
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        return static::getQueryBuilder()->select(static::getQualifiedColumns($columns))->first();
    }

    /**
     * Set dynamic property.
     * 
     * @param string $name
     * @param mixed $value
     * 
     * @return void
     */
    final public function __set(string $name, mixed $value)
    {
        $this->{$name} = $value;
    }

    /**
     * Call the query builder method.
     * 
     * @param string $name
     * @param mixed $arguments
     * 
     * @return mixed
     */
    final public function __call(string $name, mixed $arguments): mixed
    {
        return call_user_func_array([static::getQueryBuilder(), $name], $arguments);
    }

    /**
     * Get static instance.
     * 
     * @return static
     */
    private static function getStaticInstance(): static
    {
        return new static;
    }

    /**
     * Get qualified columns.
     * 
     * @param array<int, string> $columns
     * 
     * @return array
     */
    private static function getQualifiedColumns(array $columns): array
    {
        return array_map(function (string $column) {
            return static::getStaticInstance()->table . '.' . $column;
        }, $columns);
    }

    /**
     * Set the query builder instance.
     * 
     * @return void
     */
    private static function setQueryBuilder(): void
    {
        static::$builder = new Builder((new Connection())->connect(), static::class);
        static::$builder->from((static::getStaticInstance())->table);
    }

    /**
     * Get the query builder instance.
     * 
     * @return Builder
     */
    private static function getQueryBuilder(): Builder
    {
        static::setQueryBuilder();

        return static::$builder;
    }
}
