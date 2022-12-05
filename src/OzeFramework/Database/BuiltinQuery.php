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
}
