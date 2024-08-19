<?php

declare(strict_types=1);

namespace OzeFramework\Container\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
