<?php
declare(strict_types=1);

namespace Wex;

use \Zend\Config\Reader\Ini;
use \Zend\Config\Config;
use \Zend\Db\Adapter\Adapter;
use \Zend\Db\Sql\Sql;
use \Wex\ActiveRecord;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class App
{
    static  $config;
    static  $uri;
    static  $db;
    static  $sql;

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

        ActiveRecord::setAdapter(static::$db);
        ActiveRecord::setSql(static::$sql);
    }

    public static function readConfig(string $filename) : Config
    {
        $reader = new Ini;
        return new Config($reader->fromFile($filename));
    }

    private function route(ServerRequest $request) : void
    {
        static::$uri = $request->getUri();;
    }

    public function run(callable $callback) : void
    {
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );

        $this->route($request);

        /**
         * @todo Create Middleware
         */
        $response = new Response;
        $response->getBody()->write(get_class( $this ));

        $callback($this, $request, $response);
    }

    public function serve(Response $response)
    {
        echo $response->getBody();
    }

}