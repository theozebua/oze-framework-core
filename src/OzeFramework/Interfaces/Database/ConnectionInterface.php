<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\Database;

use PDO;
use PDOException;

interface ConnectionInterface
{
    /**
     * Connect to a database.
     * 
     * @throws PDOException
     * 
     * @return PDO
     */
    public function connect(): PDO;
}
