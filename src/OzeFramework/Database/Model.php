<?php

declare(strict_types=1);

namespace OzeFramework\Database;

use OzeFramework\App\App;
use OzeFramework\Exceptions\Database\ConfigNotFoundException;
use OzeFramework\Interfaces\Database\ModelInterface;
use OzeFramework\Http\Response;
use PDO;

/**
 * @method array all(array $columns = ['*']) Get all data.
 * @method array|false getWhere(string $key, string $operator = '=', mixed $value, array $columns = ['*']) Get data based on given condition.
 * @method object|false firstWhere(string $key, string $operator = '=', mixed $value, array $columns = ['*']) Get first data based on given condition.
 */
abstract class Model implements ModelInterface
{
    /**
     * The Response class.
     * 
     * @var Response $response
     */
    private Response $response;

    /**
     * Model name.
     * 
     * @var string $model
     */
    protected string $model;

    /**
     * Table name.
     * 
     * @var string $table
     */
    protected string $table;

    /**
     * Builtin Query.
     * 
     * @var BuiltinQuery $builtinQuery
     */
    private BuiltinQuery $builtinQuery;
    private QueryBuilder $queryBuilder;

    private array $properties;

    private PDO $dbh;

    /**
     * Model constructor.
     * 
     * @throws ConfigNotFoundException
     * 
     * @return void
     */
    final public function __construct()
    {
        $configDirectory = App::$rootDir . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
        $dbConfig        = $configDirectory . 'database.php';

        if (!file_exists($dbConfig)) {
            $this->response->statusCode(Response::NOT_FOUND);
            throw new ConfigNotFoundException(sprintf("Config file \"%s\" is not found in %s", 'database.php', $configDirectory));
        }

        $config             = require $dbConfig;
        $this->dbh          = (new Connection($config))->connect();

        $this->queryBuilder = new QueryBuilder($this->dbh, $this::class, $this->table);
    }

    final public function __set(string $name, mixed $value): void
    {
        $this->properties[$name] = $value;
    }

    final public function __get(string $name): mixed
    {
        return $this->properties[$name];
    }

    final public function __call(string $name, mixed $arguments): mixed
    {
        return call_user_func_array([$this->builtinQuery, $name], $arguments);
    }

    final static public function __callStatic(string $name, mixed $arguments)
    {
        $modelInstance = new (get_called_class());
        return call_user_func_array([$modelInstance->queryBuilder, $name], $arguments);   
    }
}
