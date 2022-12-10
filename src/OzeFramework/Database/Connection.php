<?php

declare(strict_types=1);

namespace OzeFramework\Database;

use OzeFramework\Interfaces\Database\ConnectionInterface;
use OzeFramework\Response\Response;
use PDO;
use PDOException;

final class Connection implements ConnectionInterface
{
    /**
     * The Response class.
     * 
     * @var Response $response
     */
    private Response $response;

    /**
     * Data Source Name.
     * 
     * @var string $dsn
     */
    private string $dsn;

    /**
     * Database Username.
     * 
     * @var string $username
     */
    private string $username;

    /**
     * Database Password.
     * 
     * @var string $password
     */
    private string $password;

    /**
     * Create a database connection.
     * 
     * @param array<string, mixed> $config
     * 
     * @return void
     */
    final public function __construct(array $config)
    {
        [
            'dsn_prefix' => $dsnPrefix,
            'host'       => $host,
            'port'       => $port,
            'charset'    => $charset,
            'database'   => $database,
            'username'   => $username,
            'password'   => $password,
        ] = $config;

        $this->dsn      = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s', $dsnPrefix, $host, $port, $database, $charset);
        $this->username = $username;
        $this->password = $password;
        $this->response = new Response();
    }

    final public function connect(): PDO
    {
        try {
            return new PDO($this->dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            $this->response->statusCode(Response::INTERNAL_SERVER_ERROR);
            throw $e;
        }
    }
}
