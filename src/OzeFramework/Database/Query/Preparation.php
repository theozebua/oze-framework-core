<?php

declare(strict_types=1);

namespace OzeFramework\Database\Query;

trait Preparation
{
    /**
     * Prepare the SELECT statement.
     * 
     * @return void
     */
    private function prepareSelect(): void
    {
        $columns = rtrim(implode(', ', $this->bindings['select']), ', ');

        $this->query .= "SELECT {$columns} FROM {$this->table} ";
    }

    /**
     * Prepare the WHERE clause.
     * 
     * @return void
     */
    private function prepareWheres(): void
    {
        foreach ($this->bindings['wheres'] as $where) {
            [$key,, $operator, $boolean, $not] = $where;

            if (!str_contains($this->query, 'WHERE')) {
                if ($not) {
                    $this->query .= "WHERE NOT {$key} {$operator} ? ";
                    continue;
                }

                $this->query .= "WHERE {$key} {$operator} ? ";
                continue;
            }

            if ($not) {
                $this->query .= "{$boolean} NOT {$key} {$operator} ? ";
                continue;
            }

            $this->query .= "{$boolean} {$key} {$operator} ? ";
        }
    }

    /**
     * Prepare the LIMIT clause.
     * 
     * @return void
     */
    private function prepareLimit(): void
    {
        $this->query .= "LIMIT ? OFFSET ? ";
    }
}
