<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\App;

use Exception;

interface AppInterface
{
    /**
     * Setup the application.
     * 
     * @return void
     */
    public function setup(): void;

    /**
     * Run the appilcation.
     * 
     * @throws Exception
     * 
     * @return void
     */
    public function run(): void;
}
