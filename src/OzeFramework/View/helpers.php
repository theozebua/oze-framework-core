<?php

declare(strict_types=1);

use OzeFramework\View\View;

if (!function_exists('view')) {
    /**
     * Make a view.
     * 
     * @param string $view
     * @param array $data
     * 
     * @return View
     */
    function view(string $view, array $data = []): View
    {
        return View::make($view, $data);
    }
}
