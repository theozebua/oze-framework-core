<?php

declare(strict_types=1);

namespace OzeFramework\View;

use OzeFramework\App\App;
use OzeFramework\Exceptions\View\ViewNotFoundException;
use OzeFramework\Interfaces\View\ViewInterface;
use OzeFramework\Http\Response;

final class View implements ViewInterface
{
    /**
     * The Response class.
     * 
     * @var Response $response
     */
    private Response $response;

    /**
     * Default view file extension.
     * 
     * @var string $extension
     */
    private string $extension = '.php';

    /**
     * Create a view instance.
     * 
     * @param string $viewPath
     * 
     * @return void
     */
    final public function __construct(private string $view, private array $data = [])
    {
        $this->response = new Response();
    }

    /**
     * {@inheritdoc}
     */
    final public static function make(string $view, array $data = []): static
    {
        return new static($view, $data);
    }

    /**
     * {@inheritdoc}
     */
    final public function render(): string
    {
        $view = App::$rootDir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->view . $this->extension;

        if (!file_exists($view)) {
            $this->response->statusCode(Response::NOT_FOUND);
            throw new ViewNotFoundException("View not found: {$view}");
        }

        extract($this->data);

        ob_start();

        include_once $view;

        return (string) ob_get_clean();
    }

    /**
     * Render view directly without calling the render method.
     * 
     * @return string
     */
    final public function __toString(): string
    {
        return $this->render();
    }
}
