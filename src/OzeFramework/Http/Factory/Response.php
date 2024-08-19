<?php

declare(strict_types=1);

namespace OzeFramework\Http\Factory;

use OzeFramework\Http\Enums\StatusCode;
use OzeFramework\Http\Response as HttpResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class Response implements ResponseFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new HttpResponse($code, $reasonPhrase);
    }

    /**
     * Create a new response instance.
     *
     * @param int|StatusCode $statusCode
     * @param string $reasonPhrase
     * @return ResponseInterface
     */
    public static function create(int|StatusCode $statusCode = StatusCode::OK, string $reasonPhrase = ''): ResponseInterface
    {
        $statusCode = $statusCode instanceof StatusCode ? $statusCode->value : $statusCode;

        return (new static())->createResponse($statusCode, $reasonPhrase);
    }
}
