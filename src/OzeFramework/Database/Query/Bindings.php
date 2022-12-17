<?php

declare(strict_types=1);

namespace OzeFramework\Database\Query;

trait Bindings
{
    /**
     * Bind the WHERE clause.
     * 
     * @return void
     */
    private function bindWheres(): void
    {
        $wheres = $this->bindings['wheres'];

        for ($i = 0; $i < count($wheres); $i++) {
            $this->statement->bindValue($this->position, $wheres[$i][1], $this->checkParamType($wheres[$i][1]));
            $this->position += 1;
        }
    }

    /**
     * Bind the LIMIT clause.
     * 
     * @return void
     */
    private function bindLimit(): void
    {
        $limit = $this->bindings['limit'];

        for ($i = 0; $i < count($limit); $i++) {
            $this->statement->bindValue($this->position, $limit[$i], $this->checkParamType($limit[$i]));
            $this->position += 1;
        }
    }
}
