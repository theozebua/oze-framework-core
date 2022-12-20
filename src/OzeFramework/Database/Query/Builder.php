<?php

declare(strict_types=1);

namespace OzeFramework\Database\Query;

use OzeFramework\Exceptions\Database\TableNotSetException;
use OzeFramework\Interfaces\Database\Query\BuilderInterface;
use PDO;
use PDOStatement;

final class Builder implements BuilderInterface
{
    use Preparation;
    use Bindings;

    /**
     * The PDO Statement class.
     * 
     * @var PDOStatement $statement
     */
    private PDOStatement $statement;

    /**
     * The current SQL query.
     * 
     * @var string $query
     */
    private string $query = '';

    /**
     * The current query value bindings.
     * 
     * @var array $bindings
     */
    private array $bindings = [
        'select' => [],
        'wheres' => [],
        'limit' => [],
    ];

    /**
     * The current placeholder position.
     * 
     * @var int $position
     */
    private int $position = 1;

    /**
     * Create query builder instance.
     * 
     * @return void
     */
    final public function __construct(private PDO $dbh, private string $model, private string $table)
    {
        // 
    }

    /**
     * {@inheritdoc}
     */
    final public function select(array|string $columns = ['*']): self
    {
        $this->bindings['select'] = array_merge(
            $this->bindings['select'],
            is_array($columns) ? $columns : func_get_args()
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function where(string $key, mixed $value, string $operator = '=', string $boolean = 'AND', bool $useNot = false): self
    {
        $this->bindings['wheres'] = array_merge(
            $this->bindings['wheres'],
            [
                [
                    $key,
                    $value,
                    $operator,
                    $boolean,
                    $useNot,
                ]
            ]
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function whereNot(string $key, mixed $value, string $operator = '='): self
    {
        $this->where($key, $value, $operator, useNot: true);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function whereLike(string $key, mixed $value): self
    {
        $this->where($key, $value, 'LIKE');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function whereNotLike(string $key, mixed $value): self
    {
        $this->where($key, $value, 'LIKE', useNot: true);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function orWhere(string $key, mixed $value, string $operator = '='): self
    {
        $this->where($key, $value, $operator, 'OR');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function orWhereNot(string $key, mixed $value, string $operator = '='): self
    {
        $this->where($key, $value, $operator, 'OR', true);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function orWhereLike(string $key, mixed $value): self
    {
        $this->where($key, $value, 'LIKE', 'OR');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function orWhereNotLike(string $key, mixed $value): self
    {
        $this->where($key, $value, 'LIKE', 'OR', true);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function limit(int $length, int $offset = 0): self
    {
        $this->bindings['limit'] = [$length, $offset];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function get(): array
    {
        $this->build();

        return $this->statement->fetchAll();
    }

    /**
     * {@inheritdoc}
     */
    final public function first(): object
    {
        $this->build();

        return $this->statement->fetchObject($this->model);
    }

    /**
     * Build the sql query.
     * 
     * @return void
     */
    private function build(): void
    {
        $this->checkColumns();
        $this->checkTable();
        $this->prepareSelect();

        if (!empty($this->bindings['wheres'])) {
            $this->prepareWheres();
        }

        if (!empty($this->bindings['limit'])) {
            $this->prepareLimit();
        }

        $this->statement = $this->dbh->prepare($this->query);

        $this->bindWheres();
        $this->bindLimit();

        $this->statement->setFetchMode(PDO::FETCH_CLASS, $this->model);
        $this->statement->execute();
    }

    /**
     * Check if columns are empty or not.
     * 
     * @return void
     */
    private function checkColumns(): void
    {
        if (empty($this->bindings['select'])) {
            $this->bindings['select'] = ['*'];
        }
    }

    /**
     * Check if table is empty or not.
     * 
     * @throws TableNotSetException
     * 
     * @return void
     */
    private function checkTable(): void
    {
        if (!isset($this->table)) {
            throw new TableNotSetException('Table must be defined');
        }
    }

    /**
     * Check param type.
     * 
     * @param mixed $value
     * 
     * @return int
     */
    private function checkParamType(mixed $value): int
    {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        }

        if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        }

        if (is_null($value)) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }
}
