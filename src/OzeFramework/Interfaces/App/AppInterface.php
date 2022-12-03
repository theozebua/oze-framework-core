<?php

declare(strict_types=1);

namespace OzeFramework\Interfaces\App;

interface AppInterface
{
    /**
     * Run the appilcation.
     * 
     * @return void
     */
    public function run(): void;
}
