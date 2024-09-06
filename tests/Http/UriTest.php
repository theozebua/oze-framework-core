<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use InvalidArgumentException;
use OzeFramework\Http\Uri;

final class UriTest extends AbstractUri
{
    protected Uri $uri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = $this->factory->createUri($this->url);
    }

    public function testUriReturnsSchema(): void
    {
        $scheme = $this->uri->getScheme();

        $this->assertSame('http', $scheme);
    }

    public function testUriReturnsAuthority(): void
    {
        $authority = $this->uri->getAuthority();

        $this->assertSame('username:password@hostname:9090', $authority);
    }

    public function testUriReturnsUserInfo(): void
    {
        $userInfo = $this->uri->getUserInfo();

        $this->assertSame('username:password', $userInfo);
    }

    public function testUriReturnsHost(): void
    {
        $host = $this->uri->getHost();

        $this->assertSame('hostname', $host);
    }

    public function testUriReturnsPort(): void
    {
        $port = $this->uri->getPort();

        $this->assertSame(9090, $port);
    }

    public function testUriReturnsPath(): void
    {
        $path = $this->uri->getPath();

        $this->assertSame('/path', $path);
    }

    public function testUriReturnsQuery(): void
    {
        $query = $this->uri->getQuery();

        $this->assertSame('key=value', $query);
    }

    public function testUriReturnsFragment(): void
    {
        $fragment = $this->uri->getFragment();

        $this->assertSame('anchor', $fragment);
    }

    public function testUriReturnsNewObjectWithModifiedScheme(): void
    {
        $uri = $this->uri;

        $this->expectException(InvalidArgumentException::class);

        $uri->withScheme('invalid');

        $newUri = $uri->withScheme('https');

        $this->assertSame('https', $newUri->getScheme());
        $this->assertNotSame($uri, $newUri);
    }

    public function testUriReturnsNewObjectWithModifiedAuthorityAndUserInfo(): void
    {
        $uri = $this->uri;

        $newUri = $uri->withUserInfo('newusername', 'newpassword');

        $this->assertSame('newusername:newpassword', $newUri->getUserInfo());
        $this->assertSame('newusername:newpassword@hostname:9090', $newUri->getAuthority());
        $this->assertNotSame($uri, $newUri);
    }

    public function testUriReturnsNewObjectWithModifiedHost(): void
    {
        $uri = $this->uri;

        $this->expectException(InvalidArgumentException::class);

        $uri->withHost('1nv4l1d');

        $newUri = $uri->withHost('newhostname');

        $this->assertSame('newhostname', $newUri->getHost());
        $this->assertNotSame($uri, $newUri);
    }

    public function testUriReturnsNewObjectWithModifiedPort(): void
    {
        $uri = $this->uri;

        $this->expectException(InvalidArgumentException::class);

        $uri->withPort(-1);

        $newUri = $uri->withPort(8080);

        $this->assertSame(8080, $newUri->getPort());
        $this->assertNotSame($uri, $newUri);
    }

    public function testUriReturnsNewObjectWithModifiedPath(): void
    {
        $uri = $this->uri;

        $this->expectException(InvalidArgumentException::class);

        $uri->withPath('/path?key=value#anchor');

        $newUri = $uri->withPath('/newpath');

        $this->assertSame('/newpath', $newUri->getPath());
        $this->assertNotSame($uri, $newUri);
    }

    public function testUriReturnsNewObjectWithModifiedQuery(): void
    {
        $uri = $this->uri;

        $this->expectException(InvalidArgumentException::class);

        $uri->withQuery('?key=value');

        $newUri = $uri->withQuery('newkey=newvalue');

        $this->assertSame('newkey=newvalue', $newUri->getQuery());
        $this->assertNotSame($uri, $newUri);
    }

    public function testUriReturnsNewObjectWithModifiedFragment(): void
    {
        $uri = $this->uri;

        $newUri = $uri->withFragment('anchor');

        $this->assertSame('anchor', $newUri->getFragment());
        $this->assertNotSame($uri, $newUri);
    }

    public function testUriCanCastToString(): void
    {
        $uri = $this->uri;

        $this->assertSame($this->url, (string) $uri);
    }
}
