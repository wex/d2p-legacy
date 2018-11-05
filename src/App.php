<?php
declare(strict_types=1);

namespace Wex;

use \Wex\App\Response;
use \Zend\Config\Reader\Ini;
use \Zend\Config\Config;
use \Zend\Db\Adapter\Adapter;
use \Zend\Db\Sql\Sql;
use \Wex\ActiveRecord;

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

    public static function bootstrap(callable $callback) : Response
    {
        return $callback( new static );
    }

    private function __construct()
    {
        $this->debug();

        $this->configure();
        $this->route();
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

    private function route() : void
    {
        static::$uri = $_GET['_url'] ?? '';
    }

    public function run() : Response
    {
        echo '<pre>';
        print_r( static::$config );
        return new Response\Html;
    }

}