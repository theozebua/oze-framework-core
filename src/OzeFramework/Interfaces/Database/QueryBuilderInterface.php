<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\Database;

use OzeFramework\Database\Model;

interface QueryBuilderInterface
{
	public function all(): array;
	public function first(): Model;
	// public function save(): Model;
	// public function delete(): Model;
	// public function count(): Model;

	public function select(array|string $columns, array|string ...$otherColumns): self;
	// public function selectDistinct(array|string $columns, array|string ...$otherColumns): self;
	// public function from(array|string $tables, array ...$otherTables): self;

	// public function join(array|string $tables, array ...$otherTables): self;
	// public function innerJoin(array|string $tables, array ...$otherTables): self;
	// public function leftJoin(array|string $tables, array ...$otherTables): self;
	// public function rightJoin(array|string $tables, array ...$otherTables): self;

	// public function where(array|string $conditions, array ...$otherConditions): self;
	// public function andWhere(array|string $conditions, array ...$otherConditions): self;
	// public function orWhere(array|string $conditions, array ...$otherConditions): self;
	// public function whereIn(array|string $conditions, array ...$otherConditions): self;
	// public function whereNotIn(array|string $conditions, array ...$otherConditions): self;
	// public function whereNull(array|string $conditions, array ...$otherConditions): self;
	// public function whereNotNull(array|string $conditions, array ...$otherConditions): self;
	// public function whereRaw(string $condition): self;

	// public function groupBy(array|string $columns, array|string ...$otherColumns): self;
	public function limit(int $numberLimit): self;
	// public function offset(int $numberOffset): self;
	// public function orderBy(array|string $columns, array|string ...$otherColumns): self;
	// public function having(array|string $conditions, array ...$otherConditions): self;

	// planned
	// public function union(): self;
	// public function with(): self;
}
