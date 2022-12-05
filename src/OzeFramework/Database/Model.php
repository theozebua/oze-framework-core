<?php

declare(strict_types=1);

namespace OzeFramework\Database;

use OzeFramework\App\App;
use OzeFramework\Exceptions\Database\ConfigNotFoundException;
use OzeFramework\Interfaces\Database\ModelInterface;

/**
 * @method array all(array $columns = ['*'])
 */
abstract class Model implements ModelInterface
{
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

    /**
     * Model constructor.
     * 
     * @return void
     */
    final public function __construct()
    {
        $configDirectory = App::$rootDir . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
        $dbConfig        = $configDirectory . 'database.php';

        if (!file_exists($dbConfig)) {
            throw new ConfigNotFoundException(sprintf("Config file \"%s\" is not found in %s", 'database.php', $configDirectory));
        }

        $config        = require $dbConfig;
        $dbh           = (new Connection($config))->connect();
        $this->builtinQuery = new BuiltinQuery($dbh, $this->model, $this->table);
    }

    final public function __set(string $name, mixed $value)
    {
        $this->{$name} = $value;
    }

    final public function __call(string $name, mixed $arguments): mixed
    {
        return call_user_func_array([$this->builtinQuery, $name], $arguments);
    }
}
