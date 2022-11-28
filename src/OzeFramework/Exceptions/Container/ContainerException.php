<?php

declare(strict_types=1);

namespace OzeFramework\Exceptions\Container;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use OzeFramework\Exceptions\BaseException;

final class ContainerException extends Exception implements ContainerExceptionInterface, BaseException
{
    // 
}
