<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

final class UriFactoryTest extends AbstractUri
{
    public function testUriFactoryThrowsInvalidArgumentExceptionIfGivenUriIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->factory->createUri('http:///invalid.com');
    }

    public function testUriFactoryReturnsUriObjectThatImplementsPsrHttpMessageUriInterface(): void
    {
        $uri = $this->factory->createUri($this->url);

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($this->url, (string) $uri);
        $this->assertSame('http', $uri->getScheme());
        $this->assertSame('username:password@hostname:9090', $uri->getAuthority());
        $this->assertSame('username:password', $uri->getUserInfo());
        $this->assertSame('hostname', $uri->getHost());
        $this->assertSame(9090, $uri->getPort());
        $this->assertSame('/path', $uri->getPath());
        $this->assertSame('key=value', $uri->getQuery());
        $this->assertSame('anchor', $uri->getFragment());
    }

    public function testUriFactoryReturnsUriObjectThatImplementsPsrHttpMessageUriInterfaceFromGlobals(): void
    {
        $server = [
            'HTTP_HOST' => 'www.example.com',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9',
            'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, br',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_COOKIE' => 'PHPSESSID=1234567890abcdef; path=/',
            'HTTPS' => 'on',
            'SERVER_SIGNATURE' => '<address>Apache/2.4.41 (Ubuntu) Server at www.example.com Port 443</address>',
            'SERVER_SOFTWARE' => 'Apache/2.4.41 (Ubuntu)',
            'SERVER_NAME' => 'www.example.com',
            'SERVER_ADDR' => '192.168.1.1',
            'SERVER_PORT' => '443',
            'REMOTE_ADDR' => '203.0.113.195',
            'DOCUMENT_ROOT' => '/var/www/html',
            'REQUEST_SCHEME' => 'https',
            'CONTEXT_PREFIX' => '',
            'CONTEXT_DOCUMENT_ROOT' => '/var/www/html',
            'SERVER_ADMIN' => 'webmaster@example.com',
            'SCRIPT_FILENAME' => '/var/www/html/index.php',
            'REMOTE_PORT' => '56789',
            'GATEWAY_INTERFACE' => 'CGI/1.1',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'REQUEST_METHOD' => 'GET',
            'QUERY_STRING' => 'key=value',
            'REQUEST_URI' => '/index.php?key=value',
            'SCRIPT_NAME' => '/index.php',
            'PATH_INFO' => '',
            'PATH_TRANSLATED' => '/var/www/html/index.php',
            'PHP_SELF' => '/index.php',
            'REQUEST_TIME_FLOAT' => 1629804267.1234,
            'REQUEST_TIME' => 1629804267,
        ];

        $uri = $this->factory->createFromGlobals($server);

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame('https://www.example.com/index.php?key=value', (string) $uri);
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('www.example.com', $uri->getAuthority());
        $this->assertSame('', $uri->getUserInfo());
        $this->assertSame('www.example.com', $uri->getHost());
        $this->assertSame(null, $uri->getPort());
        $this->assertSame('/index.php', $uri->getPath());
        $this->assertSame('key=value', $uri->getQuery());
        $this->assertSame('', $uri->getFragment());
    }
}
