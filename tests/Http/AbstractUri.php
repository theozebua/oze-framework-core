<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use PHPUnit\Framework\TestCase;

abstract class AbstractUri extends TestCase
{
    protected string $url = 'http://username:password@hostname:9090/path?key=value#anchor';
}
