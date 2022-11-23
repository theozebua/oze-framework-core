<?php

declare(strict_types=1);

namespace Theozebua\OzeFramework\Exceptions\Container;

use Exception;
use Psr\Container\NotFoundExceptionInterface;
use Theozebua\OzeFramework\Exceptions\BaseException;

final class NotFoundException extends Exception implements NotFoundExceptionInterface, BaseException
{
    // 
}
