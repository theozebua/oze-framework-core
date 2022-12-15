<?php

declare(strict_types=1);

namespace OzeFramework\Database;

use PDO;
use PDOStatement;
use OzeFramework\Interfaces\Database\QueryBuilderInterface;

final class QueryBuilder implements QueryBuilderInterface
{
	use RawSqlConstructorTrait;

	private PDOStatement $statement;


	final public function __construct(private PDO $dbh, private string $modelClassName, private string $table)
	{
	}

	private function setStatement(): void
	{
		$this->constructRawSql();
		$this->statement = $this->dbh->prepare($this->rawSql);
		$this->statement->execute();
		$this->statement->setFetchMode(PDO::FETCH_CLASS, $this->modelClassName);
	}

	final public function all(): array
	{
		$this->setStatement();
		return $this->statement->fetchAll();
	}

	final public function first(): Model
	{
		$this->limit(1);
		$this->setStatement();
		return $this->statement->fetch();
	}

	final public function select(array|string $columns, array|string ...$otherColumns): self
	{
		$this->setSelectColumns($columns);
		$this->setSelectColumns($otherColumns);

		return $this;
	}

	final public function limit(int $numberLimit): self
	{
		$this->numberLimit = $numberLimit;
		return $this;
	}

	// final public function where(array|string $conditions, array|string ...$otherConditions): self
	// {
	// 	array_merge($this->whereConditions, $conditions);
	// 	return $this;
	// }

	// final public function join(array|string ...$tables): self
	// {
	// 	$this->joinTables = array_merge($this->joinTables, $tables);
	// 	return $this;
	// }
}
