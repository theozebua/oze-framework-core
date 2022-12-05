<?php

declare(strict_types=1);

namespace OzeFramework\Database;

use OzeFramework\Interfaces\Database\BuiltinQueryInterface;
use PDO;
use PDOStatement;

final class BuiltinQuery implements BuiltinQueryInterface
{
    /**
     * The PDOStatement class.
     * 
     * @var PDOStatement $statement
     */
    private PDOStatement $statement;

    /**
     * Create builtin query instance.
     * 
     * @param PDO $dbh
     * @param string $model
     * @param string $table
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
    final public function all(array $columns = ['*']): array
    {
        $columns = implode(', ', $columns);
        $columns = rtrim($columns, ', ');
        $sql     = "SELECT {$columns} FROM {$this->table}";

        $this->statement = $this->dbh->prepare($sql);
        $this->statement->execute();
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $this->model);

        return $this->statement->fetchAll();
    }

    /**
     * {@inheritdoc}
     */
    final public function getWhere(string $key, string $operator = '=', mixed $value, array $columns = ['*']): array|false
    {
        $columns = implode(', ', $columns);
        $columns = rtrim($columns, ', ');
        $sql     = "SELECT {$columns} FROM {$this->table} WHERE {$key} = ?";

        $this->statement = $this->dbh->prepare($sql);
        $this->statement->bindValue(1, $value, $this->checkParamType($value));
        $this->statement->execute();
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $this->model);

        return $this->statement->fetchAll();
    }

    /**
     * {@inheritdoc}
     */
    final public function firstWhere(string $key, string $operator = '=', mixed $value, array $columns = ['*']): object|false
    {
        $columns = implode(', ', $columns);
        $columns = rtrim($columns, ', ');
        $param   = ':' . $key;
        $sql     = "SELECT {$columns} FROM {$this->table} WHERE {$key} = {$param}";

        $this->statement = $this->dbh->prepare($sql);
        $this->statement->bindValue($param, $value, $this->checkParamType($value));
        $this->statement->execute();

        return $this->statement->fetchObject($this->model);
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
