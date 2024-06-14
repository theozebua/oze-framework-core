<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use OzeFramework\Http\Factory\Uri;

final class UriTest extends AbstractUri
{
    public function testUriReturnsSchema(): void
    {
        $scheme = Uri::create($this->url)->getScheme();

        $this->assertSame('http', $scheme);
    }

    public function testUriReturnsAuthority(): void
    {
        $authority = Uri::create($this->url)->getAuthority();

        $this->assertSame('username:password@hostname:9090', $authority);
    }

    public function testUriReturnsUserInfo(): void
    {
        $userInfo = Uri::create($this->url)->getUserInfo();

        $this->assertSame('username:password', $userInfo);
    }

    public function testUriReturnsHost(): void
    {
        $host = Uri::create($this->url)->getHost();

        $this->assertSame('hostname', $host);
    }

    public function testUriReturnsPort(): void
    {
        $port = Uri::create($this->url)->getPort();

        $this->assertSame(9090, $port);
    }

    public function testUriReturnsPath(): void
    {
        $path = Uri::create($this->url)->getPath();

        $this->assertSame('/path', $path);
    }

    public function testUriReturnsQuery(): void
    {
        $query = Uri::create($this->url)->getQuery();

        $this->assertSame('arg=value', $query);
    }

    public function testUriReturnsFragment(): void
    {
        $fragment = Uri::create($this->url)->getFragment();

        $this->assertSame('anchor', $fragment);
    }
}
