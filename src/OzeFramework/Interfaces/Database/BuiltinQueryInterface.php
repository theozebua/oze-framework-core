<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\Database;

interface BuiltinQueryInterface
{
    /**
     * Get all data.
     * 
     * @param array $columns
     * 
     * @return array
     */
    public function all(array $columns = ['*']): array;

    /**
     * Get data based on given condition.
     * 
     * @param string $key
     * @param string $operator
     * @param array $columns
     * @param mixed $value
     * 
     * @return array|false
     */
    public function getWhere(string $key, string $operator = '=', mixed $value, array $columns = ['*']): array|false;

    /**
     * Get first data based on given condition.
     * 
     * @param string $key
     * @param string $operator
     * @param array $columns
     * @param mixed $value
     * 
     * @return object|false
     */
    public function firstWhere(string $key, string $operator = '=', mixed $value, array $columns = ['*']): object|false;
}
