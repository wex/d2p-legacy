<?php
declare(strict_types=1);

namespace Wex;

use Aura\Router\Route;
use Wex\Controller\Response;

abstract class Controller
{
    static  $layout     = 'default';
    const   formatter   = 'twig';

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

    public static function getViewPath(string $name = null)
    {
        if (is_null($name)) $name = static::class;

        $name = str_replace(['Controller', '\\'], ['', '/'], $name);

        return strtolower($name);
    }

    public static function getViewName(string $name)
    {
        $name = str_replace('Action', '', $name);

        return strtolower($name);
    }

    public static function autoload(string $class) : void
    {
        $controllerPath = __ROOT__ . '/app/controllers';
        $controllerFile = $controllerPath . "/{$class}.php";

        @include_once $controllerFile;
    }

    public static function route(Route $route) : callable
    {
        if ($route->handler === 'wildcard') {
            $controllerClass    = static::getClass($route->attributes['parameters']);
            $controllerMethod   = static::getMethod($route->attributes['parameters']);
        } else {
            list($controllerClass, $controllerMethod) = explode('@', $route->handler);
        }

        static::autoload($controllerClass);

        return function(array $parameters = []) use ($controllerClass, $controllerMethod) {
            App::$controller    = new $controllerClass;
            App::$action        = $controllerMethod;

            return call_user_func([App::$controller, 'call'], App::$action, $parameters);
        };

        return new $controllerClass;
    }

    public function call($methodName, array $parameters = [])
    {
        $result = call_user_func_array([$this, $methodName], $parameters);
        echo '<pre>';
        printf("Called: %s\n", $methodName);
        printf("Parameters: %s\n", print_r($parameters, true));
        printf("Got: ");
        var_dump( $result );
        echo "\n\n";
        printf("This should return thru formatter: %s\n", static::formatter);
        $response = Response::factoryResponse(static::formatter);
        $tmp = $response->run( static::getViewPath(get_class($this)), static::getViewName($methodName) );
        var_dump( $response ); exit;
        $tmp = $response->run( static::getViewPath(get_class($this)), static::getViewName($methodName) );
        var_dump( $tmp );
        var_dump($response);
        exit;
    }
}