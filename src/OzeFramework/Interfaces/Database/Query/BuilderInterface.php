<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\Database\Query;

interface BuilderInterface
{
    /**
     * Add SELECT statement to the sql query.
     * 
     * @param array<int, string>|string $columns
     * 
     * @return self
     */
    public function select(array|string $columns = ['*']): self;

    /**
     * Add FROM clause to the sql query.
     * 
     * @param ?string $table
     * 
     * @return self
     */
    public function from(?string $table = null): self;

    /**
     * Add WHERE clause to the sql query.
     * 
     * @param string $key
     * @param mixed $value
     * @param string $operator
     * @param string $boolean
     * @param bool $useNot
     * 
     * @return self
     */
    public function where(string $key, mixed $value, string $operator = '=', string $boolean = 'AND', bool $useNot = false): self;

    /**
     * Add WHERE NOT clause to the sql query.
     * 
     * @param string $key
     * @param mixed $value
     * @param string $operator
     * 
     * @return self
     */
    public function whereNot(string $key, mixed $value, string $operator = '='): self;

    /**
     * Add WHERE LIKE clause to the sql query.
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return self
     */
    public function whereLike(string $key, mixed $value): self;

    /**
     * Add WHERE NOT LIKE clause to the sql query.
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return self
     */
    public function whereNotLike(string $key, mixed $value): self;

    /**
     * Add OR WHERE clause to the sql query.
     * 
     * @param string $key
     * @param mixed $value
     * @param string $operator
     * 
     * @return self
     */
    public function orWhere(string $key, mixed $value, string $operator = '='): self;

    /**
     * Add OR WHERE NOT clause to the sql query.
     * 
     * @param string $key
     * @param mixed $value
     * @param string $operator
     * 
     * @return self
     */
    public function orWhereNot(string $key, mixed $value, string $operator = '='): self;

    /**
     * Add OR WHERE LIKE clause to the sql query.
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return self
     */
    public function orWhereLike(string $key, mixed $value): self;

    /**
     * Add OR WHERE NOT LIKE clause to the sql query.
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return self
     */
    public function orWhereNotLike(string $key, mixed $value): self;

    /**
     * Add LIMIT clause to the sql query.
     * 
     * @param int $length
     * @param int $offset
     * 
     * @return self
     */
    public function limit(int $length, int $offset = 0): self;

    /**
     * Get all data.
     * 
     * @return array<int, object>
     */
    public function get(): array;

    /**
     * Get the first data.
     * 
     * @return object|array
     */
    public function first(): object|array;
}
