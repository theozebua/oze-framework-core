<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use InvalidArgumentException;
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

    public function testUriReturnsNewObjectWithModifiedScheme(): void
    {
        $uri = Uri::create($this->url);

        $this->expectException(InvalidArgumentException::class);

        $uri->withScheme('invalid');

        $newUri = $uri->withScheme('https');

        $this->assertSame('https', $newUri->getScheme());
        $this->assertNotSame($uri, $newUri);
    }

    public function testUriReturnsNewObjectWithModifiedAuthorityAndUserInfo(): void
    {
        $uri = Uri::create($this->url);

        $newUri = $uri->withUserInfo('newusername', 'newpassword');

        $this->assertSame('newusername:newpassword', $newUri->getUserInfo());
        $this->assertSame('newusername:newpassword@hostname:9090', $newUri->getAuthority());
        $this->assertNotSame($uri, $newUri);
    }

    public function testUriReturnsNewObjectWithModifiedHost(): void
    {
        $uri = Uri::create($this->url);

        $this->expectException(InvalidArgumentException::class);

        $uri->withHost('1nv4l1d');

        $newUri = $uri->withHost('newhostname');

        $this->assertSame('newhostname', $newUri->getHost());
        $this->assertNotSame($uri, $newUri);
    }

    public function testUriReturnsNewObjectWithModifiedPort(): void
    {
        $uri = Uri::create($this->url);

        $this->expectException(InvalidArgumentException::class);

        $uri->withPort(-1);

        $newUri = $uri->withPort(8080);

        $this->assertSame(8080, $newUri->getPort());
        $this->assertNotSame($uri, $newUri);
    }

    // TODO
    // public function testUriReturnsNewObjectWithModifiedPath(): void
    // {
    //     $uri = Uri::create($this->url);


    // }
}
