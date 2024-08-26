<?php

declare(strict_types=1);

namespace OzeFramework\Http\Factory;

use InvalidArgumentException;
use OzeFramework\Http\Stream as HttpStream;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Throwable;

use function fopen;
use function fwrite;
use function in_array;
use function rewind;

class Stream implements StreamFactoryInterface
{
    protected const array VALID_MODES = ['r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+'];

    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException If the temporary resource cannot be created.
     */
    public function createStream(string $content = ''): StreamInterface
    {
        $resource = fopen('php://temp', 'r+');

        if ($resource === false) {
            throw new RuntimeException('Unable to create a temporary resource for the stream.');
        }

        fwrite($resource, $content);
        rewind($resource);

        return $this->createStreamFromResource($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        if (!in_array($mode, self::VALID_MODES, true)) {
            throw new InvalidArgumentException('Invalid mode provided.');
        }

        try {
            $resource = fopen($filename, $mode);
        } catch (Throwable) {
            throw new RuntimeException("Unable to open file: {$filename}");
        }

        return new HttpStream($resource);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException If the provided argument is not a valid resource.
     */
    public function createStreamFromResource(mixed $resource): StreamInterface
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException('Invalid resource provided.');
        }

        return new HttpStream($resource);
    }
}
