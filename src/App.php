<?php
declare(strict_types=1);

namespace Wex;

use Zend\Config\Reader\Ini;
use Zend\Config\Config;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Aura\Router\RouterContainer;
use Aura\Router\Route;
use Wex\ActiveRecord;
use Wex\App\NoRouteException;

class App
{
    static  $config;
    static  $uri;
    static  $db;
    static  $sql;
    static  $router;
    static  $controller;
    static  $action;

    public function __clone()
    {
        throw new \RuntimeException('This is a singleton.');
    }

    public static function bootstrap() : App
    {
        return new static;
    }

    private function __construct()
    {
        $this->debug();
        $this->configure();
        (require __ROOT__ . '/app/routes.php')(static::$router->getMap());

        static::$router->getMap()->route('wildcard', '/')->wildcard('parameters');

        Session::initialize( static::$config->app->key );
    }

    private function debug() : void
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }

    private function configure() : void
    {
        static::$config = new Config([], false);
        static::$config->merge( static::readConfig(__ROOT__ . '/.config') );

        static::$db     = new Adapter( static::$config->database->toArray() );
        static::$sql    = new Sql(static::$db);

        static::$router = new RouterContainer;

        ActiveRecord::setAdapter(static::$db);
        ActiveRecord::setSql(static::$sql);
    }

    public static function readConfig(string $filename) : Config
    {
        $reader = new Ini;
        return new Config($reader->fromFile($filename));
    }

    private function route(ServerRequest $request) : Route
    {
        $matcher = static::$router->getMatcher();

        $route = $matcher->match($request);

        if (!$route) throw new NoRouteException("Route not found", 404);

        return $route;
    }

    protected function getRequest() : ServerRequest
    {
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );

        static::$uri = $request->getUri()->withPath('/' . $_REQUEST['_url'] );
        
        return $request->withUri(static::$uri);
    }

    public function run(callable $callback) : void
    {
        $request = $this->getRequest();

        $response = new Response;

        try {

            $route = $this->route($request);
            if (is_callable($route->handler)) {
                throw new \InvalidArgumentException("Routing with Closures is not implemented.");
            } else {
                
                Controller::route($route);

                echo '<pre>';
                var_dump( Controller::getClass( $route->attributes['parameters'] ) );
                var_dump( Controller::getMethod( $route->attributes['parameters'] ) );
                print_r( $route );
                print_r( $_REQUEST );
                exit;
            }

        } catch (NoRouteException $e) {

            $response = $response->withStatus($e->getCode());

        } catch (\Exception $e) {

            $response = $response->withStatus(500);

        }

        $callback->call($this, $request, $response);
    }

    public function serve(Response $response)
    {
        header(sprintf("HTTP/%s %d %s", 
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        ));

        echo $response->getBody();
    }

}