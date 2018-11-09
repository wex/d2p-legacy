<?php
declare(strict_types=1);

namespace Wex;

use Aura\Router\Route;

abstract class Controller
{
    public static function getClass(array $parameters) : string
    {
        if (count($parameters) > 2) {
            $class = implode('\\', array_map(function($v) { return ucfirst(strtolower($v)); }, array_slice($parameters, 0, -1)));
        } else {
            $class = ucfirst(strtolower($parameters[0] ?? 'index'));
        }

        return "{$class}Controller";
    }

    public static function getMethod(array $parameters) : string
    {
        if (count($parameters) > 2) {
            $method = strtolower(array_pop($parameters));
        } else {
            $method = strtolower($parameters[1] ?? 'index');
        }

        return "{$method}Action";
    }

    public static function route(Route $route) : Controller
    {
        if ($route->handler === 'wildcard') {
            $controllerClass = static::getClass($route->attributes['parameters']);
        } else {
            list($controllerClass, $controllerMethod) = explode('@', $route->handler);
        }
            
        return new $controllerClass;
    }
}