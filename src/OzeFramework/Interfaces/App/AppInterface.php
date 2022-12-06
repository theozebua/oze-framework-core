<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\App;

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
     * @return void
     */
    public function run(): void;
}
