<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\Database;

interface ModelInterface
{
    /**
     * Get all data.
     * 
     * @param array|string $columns
     * 
     * @return array
     */
    public static function all(array|string $columns = ['*']): array;

    /**
     * Get the first data.
     * 
     * @param array|string $columns
     * 
     * @return array
     */
    public static function first(array|string $columns = ['*']): object;
}
