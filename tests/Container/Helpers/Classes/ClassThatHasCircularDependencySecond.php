<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Container\Helpers\Classes;

class ClassThatHasCircularDependencySecond
{
    public function __construct(public ClassThatHasCircularDependencyFirst $classThatHasCircularDependencyFirst)
    {
        //
    }
}
