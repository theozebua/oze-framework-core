<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\Database;

use PDO;

interface ConnectionInterface
{
    /**
     * Connect to a database.
     * 
     * @return PDO
     */
    public function connect(): PDO;
}
