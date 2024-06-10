<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use InvalidArgumentException;
use OzeFramework\Http\Enums\StatusCode;
use Psr\Http\Message\ResponseInterface;

use function sprintf;

class Response extends Message implements ResponseInterface
{
    /**
     * Minimum status code.
     *
     * @var int
     */
    protected const MIN_STATUS_CODE = 100;

    /**
     * Maximum status code.
     *
     * @var int
     */
    protected const MAX_STATUS_CODE = 599;

    /**
     * Status code.
     */
    protected int $statusCode;

    /**
     * Reason phrase.
     */
    protected string $reasonPhrase;

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $clone = clone $this;

        $clone->setStatusCode($code, $reasonPhrase);

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase ?: StatusCode::tryFrom($this->statusCode)?->getReasonPhrase() ?? '';
    }

    /**
     * Set status code.
     *
     * @throws InvalidArgumentException
     * @return Response
     */
    public function setStatusCode(int|StatusCode $statusCode, string $reasonPhrase = ''): self
    {
        $this->validateStatusCode($statusCode);

        $this->statusCode = $statusCode instanceof StatusCode ? $statusCode->value : $statusCode;
        $this->reasonPhrase = $reasonPhrase ?: $this->getReasonPhrase();

        return $this;
    }

    /**
     * Validate status code.
     *
     * @param int $statusCode
     * @throws InvalidArgumentException
     * @return void
     */
    protected function validateStatusCode(int $statusCode): void
    {
        if ($statusCode < self::MIN_STATUS_CODE || $statusCode > self::MAX_STATUS_CODE) {
            throw new InvalidArgumentException(
                sprintf(
                    'Status code must be between %d and %d; received %d',
                    self::MIN_STATUS_CODE,
                    self::MAX_STATUS_CODE,
                    $statusCode,
                ),
            );
        }
    }
}
