<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Container\Helpers\Classes;

class ClassThatHasCircularDependencyFirst
{
    public function __construct(public ClassThatHasCircularDependencySecond $classThatHasCircularDependencySecond)
    {
        //
    }
}
