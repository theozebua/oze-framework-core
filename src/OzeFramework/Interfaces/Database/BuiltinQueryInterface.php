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
}
