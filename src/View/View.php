<?php

declare(strict_types=1);

namespace Theozebua\OzeFramework\View;

use Theozebua\OzeFramework\Exceptions\View\ViewNotFoundException;
use Theozebua\OzeFramework\Interfaces\View\ViewInterface;

final class View implements ViewInterface
{
    /**
     * Default view file extension.
     * 
     * @var string $extension
     */
    private string $extension = '.php';

    /**
     * Create a view instance.
     * 
     * @param string $view
     * @param array $data
     * 
     * @return void
     */
    final public function __construct(private string $view, private array $data = [])
    {
        $this->render();
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
        $view = $this->view . $this->extension;

        if (!file_exists($view)) {
            throw new ViewNotFoundException("View not found: {$view}");
        }

        extract($this->data);

        ob_start();

        include_once $view;

        return (string) ob_get_flush();
    }
}
