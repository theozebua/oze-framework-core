<?php

namespace OzeFramework\Database;

trait RawSqlConstructorTrait
{
	private string $rawSql;

	private array 	$fromTables = [];
	private array 	$selectColumns = [];
	private ?int 	$numberLimit = null;
	private array 	$whereConditions = [];
	private array 	$orWhereConditions = [];
	private array 	$joinTables = [];

	private function constructRawSql(): void
	{
		$this->constructSelect();
		$this->constructJoin();
		$this->constructWhere();
		$this->constructLimit();
	}

	private function constructWhere(): void
	{
		if ($this->whereConditions) {
			$this->rawSql .= "\nWHERE";

			foreach ($this->whereConditions as $i => $condition) {
				if ($i) {
					$this->rawSql .= "AND";
				}

				$this->rawSql .= "\n{$condition[0]} {$condition[1]} {$condition[2]}";
			}
		}
	}

	private function constructSelect(): void
	{
		if (!$this->selectColumns) {
			$this->selectColumns = ['*'];
		}

		$columns		= implode(', ', $this->selectColumns);
		$columns		= rtrim($columns, ', ');
		$this->rawSql	= "SELECT {$columns} FROM {$this->table}";
	}

	private function constructJoin(): void
	{
		foreach ($this->joinTables as $join) {
			$this->rawSql .= "\nJOIN {$join[0]} ON {$join[1]} {$join[2]} {$join[3]}";
		}
	}

	private function constructLimit(): void
	{
		if ($this->numberLimit) {
			$this->rawSql .= "\nLIMIT {$this->numberLimit}";
		}
	}

	final public function setSelectColumns($value): void
	{
		if (is_array($value)) {
			foreach ($value as $subValue) {
				$this->setSelectColumns($subValue);
			}
		} else {
			$this->selectColumns[] = $value;
		}
	}
}
