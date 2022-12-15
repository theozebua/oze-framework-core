<?php

declare(strict_types=1);

namespace OzeFramework\Env;

use Dotenv\Dotenv;
use OzeFramework\App\App;
use OzeFramework\Exceptions\Env\KeyNotFoundException;
use OzeFramework\Interfaces\Env\EnvironmentInterface;
use OzeFramework\Http\Response;

final class Environment implements EnvironmentInterface
{
    protected string $DATABASE_HOST;
    protected string $DATABASE_PORT;
    protected string $DATABASE_NAME;
    protected string $DATABASE_USER;
    protected string $DATABASE_PASS;

    /**
     * The Response class.
     * 
     * @var Response $response
     */
    private Response $response;

    /**
     * Create Environment instance.
     * 
     * @return void
     */
    final public function __construct()
    {
        Dotenv::createImmutable(App::$rootDir)->safeLoad();

        $this->response = new Response();
    }

    /**
     * {@inheritdoc}
     */
    final public function get(string $key): mixed
    {
        if (!$this->has($key)) {
            $this->response->statusCode(Response::INTERNAL_SERVER_ERROR);
            throw new KeyNotFoundException("Key \"{$key}\" is not found in environment variables");
        }

        return $this->{$key};
    }

    /**
     * {@inheritdoc}
     */
    final public function has(string $key): bool
    {
        return property_exists($this, $key);
    }

    /**
     * {@inheritdoc}
     */
    final public function all(): object
    {
        return $this;
    }

    final public function __set(string $name, mixed $value)
    {
        $this->{$name} = $value;
    }
}
