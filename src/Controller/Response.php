<?php
declare(strict_types=1);

namespace Wex\Controller;

use Wex\App;

abstract class Response
{
    static $routeGenerator = null;

    public abstract function render(string $viewPath, string $viewFile, string $layout, array $data);
}