<?php

declare(strict_types=1);

namespace OzeFramework\Http\Factory;

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
}
