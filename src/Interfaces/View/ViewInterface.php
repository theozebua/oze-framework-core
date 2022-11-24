<?php

declare(strict_types=1);

namespace Theozebua\OzeFramework\Interfaces\View;

use Theozebua\OzeFramework\Exceptions\View\ViewNotFoundException;

interface ViewInterface
{
    /**
     * Make a view.
     * 
     * @param string $view
     * @param array $data
     * 
     * @return static
     */
    public static function make(string $view, array $data = []): static;

    /**
     * Render a view.
     * 
     * @throws ViewNotFoundException
     * 
     * @return string
     */
    public function render(): string;
}
