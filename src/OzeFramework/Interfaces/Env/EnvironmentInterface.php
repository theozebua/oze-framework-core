<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\Env;

interface EnvironmentInterface
{
    /**
     * Get environment variable by the given key.
     * 
     * @param string $key
     * 
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Check if the given key is exists in environment variable.
     * 
     * @param string $key
     * 
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get all environment variables.
     * 
     * @return object
     */
    public function all(): object;
}
