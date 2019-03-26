<?php

return function(\Aura\Router\Map $router) {
    
    $router->route('cms', '/', 'TestController@view')->wildcard('parameters');

    return $router;
};