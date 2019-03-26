<?php

use Wex\App;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

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

require '../vendor/autoload.php';

App::bootstrap()->run(function(ServerRequest $request, Response $response) {
    $this->serve( $response );
});