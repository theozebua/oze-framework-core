<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use InvalidArgumentException;
use OzeFramework\Http\Factory\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

use function file_put_contents;
use function fopen;
use function fwrite;
use function rewind;
use function unlink;

final class StreamFactoryTest extends TestCase
{
    protected Stream $factory;

    protected function setUp(): void
    {
        $this->factory = new Stream();
    }

    public function testStreamFactoryReturnsStreamObjectThatImplementsStreamInterface(): void
    {
        $stream = $this->factory->createStream('Hello, OzeFramework!');

        $this->assertInstanceOf(StreamInterface::class, $stream);
    }

    public function testCreateStreamWithContent(): void
    {
        $content = 'Hello, OzeFramework!';
        $stream = $this->factory->createStream($content);

        $this->assertEquals($content, $stream->getContents());
    }

    public function testCreateStreamFromFile(): void
    {
        $filename = 'testfile.txt';

        file_put_contents($filename, 'Test file content.');

        $stream = $this->factory->createStreamFromFile($filename);

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals('Test file content.', $stream->getContents());

        unlink($filename);
    }

    public function testCreateStreamFromFileThrowsExceptionForInvalidMode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->createStreamFromFile('testfile.txt', 'invalid_mode');
    }

    public function testCreateStreamFromFileThrowsExceptionForNonExistentFile(): void
    {
        $this->expectException(RuntimeException::class);
        $this->factory->createStreamFromFile('nonexistentfile.txt');
    }

    public function testCreateStreamFromResource(): void
    {
        $resource = fopen('php://temp', 'r+');

        fwrite($resource, 'Resource content');
        rewind($resource);

        $stream = $this->factory->createStreamFromResource($resource);

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals('Resource content', $stream->getContents());
    }

    public function testCreateStreamFromResourceThrowsExceptionForInvalidResource(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->factory->createStreamFromResource('invalid_resource');
    }
}
