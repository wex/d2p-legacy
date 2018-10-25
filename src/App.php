<?php

namespace Wex;

use \Zend\Config;

class App
{
    static  $db;
    static  $config;
    static  $uri;

    public function __clone() {
        throw new \RuntimeException('This is a singleton.');
    }

    public static function bootstrap(callable $callback)
    {
        $callback( new static );
    }

    private function __construct()
    {
        $this->configure();
        $this->route();
    }

    private function configure()
    {
        
    }

    private function route()
    {
        static::$uri = $_GET['_uri'] ?? '';
        echo '<pre>';
        print_r( $_SERVER );
        echo '</pre>';
    }

    public function run()
    {

    }

}