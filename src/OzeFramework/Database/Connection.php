<?php

declare(strict_types=1);

namespace OzeFramework\Database;

use OzeFramework\App\App;
use OzeFramework\Exceptions\Database\ConfigNotFoundException;
use OzeFramework\Interfaces\Database\ConnectionInterface;
use OzeFramework\Http\Response;
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
     * @throws ConfigNotFoundException
     * 
     * @return void
     */
    final public function __construct()
    {
        $this->response = new Response();

        [
            'dsn_prefix' => $dsnPrefix,
            'host'       => $host,
            'port'       => $port,
            'charset'    => $charset,
            'database'   => $database,
            'username'   => $username,
            'password'   => $password,
        ] = $this->getDatabaseConfig();

        $this->dsn      = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s', $dsnPrefix, $host, $port, $database, $charset);
        $this->username = $username;
        $this->password = $password;
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

    private function getDatabaseConfig(): array
    {
        $configDirectory = App::$rootDir . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
        $config          = $configDirectory . 'database.php';

        if (!file_exists($config)) {
            $this->response->statusCode(Response::NOT_FOUND);
            throw new ConfigNotFoundException(sprintf("Config file \"%s\" is not found in %s", 'database.php', $configDirectory));
        }

        return require $config;
    }
}
