<?php

declare(strict_types=1);

use OzeFramework\App\App;

if (!function_exists('env')) {
    /**
     * Get environment variable by the given key.
     * 
     * @param string $key
     * 
     * @return mixed
     */
    function env(string $key): mixed
    {
        return App::env($key);
    }
}
