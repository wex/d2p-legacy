<?php
declare(strict_types=1);

namespace Wex;

use Aura\Router\Route;
use Wex\Controller\Response;
use Wex\Controller\ResponseFactory;
use Wex\Base\Renderable;
use Wex\Controller\Acl;
use Wex\App\RedirectException;
use Wex\Controller\Session;

abstract class Controller
{
    static      $acl        = ['*' => '*'];
    static      $layout     = 'default';
    const       renderer    = 'php';

    protected   $session    = null;
    protected   $_data      = [];

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

    public static function route(Route $route) : Controller
    {
        if ($route->handler === 'wildcard') {
            $controllerClass    = static::getClass($route->attributes['parameters']);
            $controllerMethod   = static::getMethod($route->attributes['parameters']);
        } else {
            list($controllerClass, $controllerMethod) = explode('@', $route->handler);
        }

        static::autoload($controllerClass);

        App::$controller    = new $controllerClass;

        return App::$controller;
    }

    public function call(Route $route)
    {
        if ($route->handler === 'wildcard') {
            $methodName   = static::getMethod($route->attributes['parameters']);
        } else {
            list(, $methodName) = explode('@', $route->handler);
        }

        App::$action = $methodName;
        
        if (!$this->isAllowed($methodName))
            throw new \Exception("ACL Deny");
        
        $result = call_user_func_array([$this, $methodName], $route->attributes);

        if (is_object($result)) {
            return $result;
        } else if (is_array($result)) {
            $data = $this->getData();
            $data = array_merge($data, $result);
        } else if ($result === false) {
            throw new \Exception("Denied");
        } else {
            $data = $this->getData();
        }
        
        return $data ?? [];
    }

    public function getData()
    {
        return $this->_data;
    }

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->_data[$name] ?? null;
    }

    public function session()
    {
        if (null === $this->session) {
            $this->session = new Session;
        }

        return $this->session;
    }

    public function get($param)
    {
        return filter_input(INPUT_GET, $param);
    }

    public function post($param)
    {
        return filter_input(INPUT_POST, $param);
    }
    
    public function uriTo($target) 
    {
        $generator = \Wex\App::$router->getGenerator();
        $target = $generator->generate($target);
        
        return \Wex\App::$base . $target;
    }
    
    public function redirect($target = '/', $params = [])
    {
        if (strpos($target, '://') !== false) {
            $url = $target;
        } else if (strlen($target) && $target[0] !== '/') {
            $generator = \Wex\App::$router->getGenerator();
            $target = $generator->generate($target, $params);
            $url = \Wex\App::$base . $target;
        } else {
            $url = \Wex\App::$base . $target;
        }
        
        throw new RedirectException($url);
    }
    
    public function isAllowed($method)
    {
        return true;
    }
    
    public function isLogged()
    {
        return \App\User::logged();
    }
    
    public function user()
    {
        return \App\User::current();
    }
}