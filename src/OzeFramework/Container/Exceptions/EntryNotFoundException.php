<?php

declare(strict_types=1);

namespace OzeFramework\Container\Exceptions;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class EntryNotFoundException extends Exception implements NotFoundExceptionInterface
{
    //
}
