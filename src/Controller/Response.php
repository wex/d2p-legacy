<?php
declare(strict_types=1);

namespace Wex\Controller;

use Wex\App;

abstract class Response
{
    static $routeGenerator = null;

    public abstract function render(string $viewPath, string $viewFile, string $layout, array $data);

    public function get($key, $default = null)
    {

    }

    public function post($key, $default = null)
    {
        
    }

    public function uriTo($route, $params = [])
    {
        if (is_null(static::$routeGenerator)) {
            static::$routeGenerator = App::$router->getGenerator();
        }

        return static::$routeGenerator->generate($route, $params);
    }

    public function __get($name)
    {
        switch ($name) {
            case 'url':
                return App::$url;
            case 'uri':
                return App::$uri;
        }
    }
}