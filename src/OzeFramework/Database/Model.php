<?php

declare(strict_types=1);

namespace OzeFramework\Database;

use OzeFramework\App\App;
use OzeFramework\Database\Query\Builder;
use OzeFramework\Exceptions\Database\ConfigNotFoundException;
use OzeFramework\Interfaces\Database\ModelInterface;
use OzeFramework\Http\Response;

/**
 * @method Builder select(array|string $columns = ['*']) Add SELECT statement to the sql query.
 * @method Builder where(string $key, mixed $value, string $operator = '=', string $boolean = 'AND', bool $useNot = false) Add WHERE clause to the sql query.
 * @method Builder whereNot(string $key, mixed $value, string $operator = '=') Add WHERE NOT clause to the sql query.
 * @method Builder whereLike(string $key, mixed $value) Add WHERE LIKE clause to the sql query.
 * @method Builder whereNotLike(string $key, mixed $value) Add WHERE NOT LIKE clause to the sql query.
 * @method Builder orWhere(string $key, mixed $value, string $operator = '=') Add OR WHERE clause to the sql query.
 * @method Builder orWhereNot(string $key, mixed $value, string $operator = '=') Add OR WHERE NOT clause to the sql query.
 * @method Builder orWhereLike(string $key, mixed $value) Add OR WHERE LIKE clause to the sql query.
 * @method Builder orWhereNotLike(string $key, mixed $value) Add OR WHERE NOT LIKE clause to the sql query.
 * @method Builder limit(int $length, int $offset = 0) Add LIMIT clause to the sql query.
 * @method array get() Get all data.
 * @method object first() Get the first data.
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
     * Table name.
     * 
     * @var string $table
     */
    protected string $table;

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
        $this->builder      = new Builder((new Connection($config))->connect(), $this::class, $this->table);
    }

    /**
     * Set dynamic property.
     * 
     * @param string $name
     * @param mixed $value
     * 
     * @return void
     */
    final public function __set(string $name, mixed $value)
    {
        $this->{$name} = $value;
    }

    /**
     * Call the query builder method.
     * 
     * @param string $name
     * @param mixed $arguments
     * 
     * @return mixed
     */
    final public function __call(string $name, mixed $arguments): mixed
    {
        return call_user_func_array([$this->builder, $name], $arguments);
    }
}
