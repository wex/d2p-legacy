<?php

use Wex\App;

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

define('__ROOT__', realpath(__DIR__ . '/../'));
require __ROOT__ . '/vendor/autoload.php';

session_start();

try {

    $request = App::bootstrap(function($app) {
        return $app->run();
    });

    /**
     * @todo Fix this - Lazy Man's PSR-over9000
     */
    echo $request;

} catch (\Exception $e) {

    /**
     * @todo Add error handling!
     */
    echo "<pre>{$e}</pre>";
    
}