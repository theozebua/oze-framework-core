<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Container\Helpers\Classes;

class ClassThatHasDependencies
{
    public function __construct(public RegularClass $regularClass)
    {
        //
    }
}
