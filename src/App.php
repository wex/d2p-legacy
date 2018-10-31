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
        $this->configure();
        $this->route();
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

        $page = \Wex\Page::load(1);

        var_dump( $page );

        var_dump( $page->fields );

        foreach ($page->fields as $field) {
            var_dump( $field );
        }

        return new Response\Html;
    }

}