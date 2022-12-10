<?php

declare(strict_types=1);

namespace OzeFramework\Http;

use OzeFramework\Interfaces\Http\ResponseInterface;

class Response implements ResponseInterface
{
    /**
     * The request succeeded.
     * 
     * @var int
     */
    public const OK = 200;

    /**
     * The request succeeded, and a new resource was created as a result.
     * 
     * @var int
     */
    public const CREATED = 201;

    /**
     * There is no content to send for this request, but the headers may be useful. 
     * The user agent may update its cached headers for this resource with the new ones
     * 
     * @var int
     */
    public const NO_CONTENT = 204;

    /**
     * The URL of the requested resource has been changed permanently. 
     * The new URL is given in the response.
     * 
     * @var int
     */
    public const MOVED_PERMANENTLY = 301;

    /**
     * This response code means that the URI of requested resource has been changed temporarily. 
     * Further changes in the URI might be made in the future. 
     * Therefore, this same URI should be used by the client in future requests.
     * 
     * @var int
     */
    public const FOUND = 302;

    /**
     * The server cannot or will not process the request due to something 
     * that is perceived to be a client error 
     * (e.g., malformed request syntax, invalid request message framing, or deceptive request routing).
     * 
     * @var int
     */
    public const BAD_REQUEST = 400;

    /**
     * Although the HTTP standard specifies "unauthorized", semantically this response means "unauthenticated". 
     * That is, the client must authenticate itself to get the requested response.
     * 
     * @var int
     */
    public const UNAUTHORIZED = 401;

    /**
     * The client does not have access rights to the content; that is, it is unauthorized, 
     * so the server is refusing to give the requested resource. 
     * Unlike 401 Unauthorized, the client's identity is known to the server.
     * 
     * @var int
     */
    public const FORBIDDEN = 403;

    /**
     * The server cannot find the requested resource. 
     * In the browser, this means the URL is not recognized. 
     * In an API, this can also mean that the endpoint is valid but the resource itself does not exist. 
     * Servers may also send this response instead of 403 Forbidden 
     * to hide the existence of a resource from an unauthorized client. 
     * This response code is probably the most well known due to its frequent occurrence on the web.
     * 
     * @var int
     */
    public const NOT_FOUND = 404;

    /**
     * The request method is known by the server but is not supported by the target resource.
     * 
     * @var int
     */
    public const METHOD_NOT_ALLOWED = 405;

    /**
     * The server has encountered a situation it does not know how to handle.
     * 
     * @var int
     */
    public const INTERNAL_SERVER_ERROR = 500;

    /**
     * The server is not ready to handle the request. 
     * Common causes are a server that is down for maintenance or that is overloaded.
     * 
     * @var int
     */
    public const SERVICE_UNAVAILABLE = 503;

    /**
     * {@inheritdoc}
     */
    final public function statusCode(int $code): int|bool
    {
        return http_response_code($code);
    }

    /**
     * {@inheritdoc}
     */
    final public function redirect(string $route, int $code = self::FOUND): void
    {
        $this->header('Location', $route, code: $code);
    }

    /**
     * {@inheritdoc}
     */
    final public function header(string $key, mixed $value, bool $replace = true, int $code = self::OK): void
    {
        header("{$key}: {$value}", $replace, $code);
        exit;
    }

    /**
     * {@inheritdoc}
     */
    final public function headers(array $headers): void
    {
        foreach ($headers as $key => $value) {
            $this->header($key, $value, false);
        }
    }
}
