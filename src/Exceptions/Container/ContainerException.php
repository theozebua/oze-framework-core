<?php

declare(strict_types=1);

namespace Theozebua\OzeFramework\Exceptions\Container;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Theozebua\OzeFramework\Exceptions\BaseException;

final class ContainerException extends Exception implements ContainerExceptionInterface, BaseException
{
    // 
}
