<?php
declare(strict_types=1);

namespace Wex\Controller;

abstract class Response
{
    public abstract function render(string $viewPath, string $viewFile, string $layout, array $data);
}