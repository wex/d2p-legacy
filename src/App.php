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
use Wex\App\RedirectException;
use Wex\Controller\ResponseFactory;
use Zend\Diactoros\Uri;
use Wex\Base\Renderable;

class App
{
    static  $config;
    static  $base;
    static  $uri;
    static  $url;
    static  $db;
    static  $sql;
    static  $router;
    static  $controller;
    static  $action;
    static  $debug      = false;

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
        static::$debug = true;
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

    protected function cleanUri(Uri $uri) : Uri
    {
        // Clean URI
        $uri    = $uri->withPath('/' . $_REQUEST['_url'] ?? '');
        $query  = $uri->getQuery();
        $params = [];

        parse_str($query, $params);
        unset( $params['_url'] );

        $uri    = $uri->withQuery( http_build_query($params) );

        return $uri;
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

        static::$url    = $this->cleanUri($request->getUri());
        static::$base   = (string) $request->getUri()->withPath('')->withQuery('');
        static::$uri    = static::$url->getPath() . '?' . static::$url->getQuery();
        
        return $request->withUri(static::$url);
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

                $controller = Controller::route($route);
                $data       = $controller->call($route);
                if ($data instanceof Renderable) {
                    $response->getBody()->write(
                        $data->render()
                    );
                } else {
                    $renderer   = ResponseFactory::getResponse($controller::renderer);

                    $response->getBody()->write(
                        $renderer->render(
                            Controller::getViewPath(get_class(App::$controller)),
                            Controller::getViewName(App::$action),
                            Controller::$layout,
                            $data
                        )
                    );
                }
            }

        } catch (RedirectException $e) {
            
            $response = $response->withStatus(301);
            $response = $response->withHeader('Location', $e->getMessage());
            
        } catch (NoRouteException $e) {

            $response = $response->withStatus($e->getCode());
            $response->getBody()->write($e->getMessage());

        } catch (HttpExceptionion $e) {

            $response = $response->withStatus($e->getCode());
            $response->getBody()->write($e->getMessage());

        } catch (\Exception $e) {

            if (static::$debug) throw $e;
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
        
        foreach ($response->getHeaders() as $name => $value) {
            header("{$name}: " . implode(', ', $value));
        }

        echo $response->getBody();
    }

    public static function command($name, $parameters)
    {
        throw new \Exception('Not implemented');
    }

}