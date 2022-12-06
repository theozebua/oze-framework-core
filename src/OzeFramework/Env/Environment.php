<?php

declare(strict_types=1);

namespace OzeFramework\Env;

use Dotenv\Dotenv;
use OzeFramework\App\App;
use OzeFramework\Exceptions\Env\KeyNotFoundException;
use OzeFramework\Interfaces\Env\EnvironmentInterface;

final class Environment implements EnvironmentInterface
{
    final public function __construct()
    {
        Dotenv::createImmutable(App::$rootDir)->safeLoad();
    }

    /**
     * {@inheritdoc}
     */
    final public function get(string $key): mixed
    {
        if (!$this->has($key)) {
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
