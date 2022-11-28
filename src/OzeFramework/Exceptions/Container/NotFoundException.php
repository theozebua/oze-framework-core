<?php

declare(strict_types=1);

namespace OzeFramework\Exceptions\Container;

use Exception;
use Psr\Container\NotFoundExceptionInterface;
use OzeFramework\Exceptions\BaseException;

final class NotFoundException extends Exception implements NotFoundExceptionInterface, BaseException
{
    // 
}
