<?php

declare(strict_types=1);

namespace OzeFramework\Tests\Http;

use OzeFramework\Http\Stream;
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function fopen;
use function fwrite;
use function rewind;

use const SEEK_END;

final class StreamTest extends TestCase
{
    protected Stream $stream;

    protected function setUp(): void
    {
        $resource = fopen('php://temp', 'r+');

        fwrite($resource, 'Test content');
        rewind($resource);

        $this->stream = new Stream($resource);
    }

    public function testStreamGetSize(): void
    {
        $this->assertEquals(12, $this->stream->getSize());
    }

    public function testStreamTell(): void
    {
        $this->stream->seek(4);
        $this->assertEquals(4, $this->stream->tell());
    }

    public function testStreamTellThrowsExceptionWhenDetached(): void
    {
        $this->stream->detach();
        $this->expectException(RuntimeException::class);
        $this->stream->tell();
    }

    public function testStreamEof(): void
    {
        $this->stream->seek(0, SEEK_END);
        $this->stream->read(1);
        $this->assertTrue($this->stream->eof());
    }

    public function testStreamIsSeekable(): void
    {
        $this->assertTrue($this->stream->isSeekable());
    }

    public function testStreamSeekAndRewind(): void
    {
        $this->stream->seek(4);
        $this->assertEquals(4, $this->stream->tell());

        $this->stream->rewind();
        $this->assertEquals(0, $this->stream->tell());
    }

    public function testStreamIsWritable(): void
    {
        $this->assertTrue($this->stream->isWritable());
    }

    public function testStreamWrite(): void
    {
        $this->stream->seek(0, SEEK_END);
        $this->stream->write(' More data');
        $this->stream->rewind();
        $this->assertEquals('Test content More data', $this->stream->getContents());
    }

    public function testStreamIsReadable(): void
    {
        $this->assertTrue($this->stream->isReadable());
    }

    public function testStreamRead(): void
    {
        $this->assertEquals('Test', $this->stream->read(4));
    }

    public function testStreamGetContents(): void
    {
        $this->assertEquals('Test content', $this->stream->getContents());
    }

    public function testStreamGetContentsThrowsExceptionWhenDetached(): void
    {
        $this->stream->detach();
        $this->expectException(RuntimeException::class);
        $this->stream->getContents();
    }

    public function testStreamGetMetadata(): void
    {
        $this->assertIsArray($this->stream->getMetadata());
        $this->assertEquals('php://temp', $this->stream->getMetadata('uri'));
        $this->assertNull($this->stream->getMetadata('non-existent-key'));
    }

    public function testStreamDetach(): void
    {
        $resource = $this->stream->detach();
        $this->assertIsResource($resource);
        $this->assertNull($this->stream->getSize());
        $this->assertNull($this->stream->detach());
    }

    public function testStreamClose(): void
    {
        $this->stream->close();
        $this->assertNull($this->stream->getSize());
        $this->assertNull($this->stream->detach());
    }
}
