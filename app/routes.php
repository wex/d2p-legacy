<?php

return function(\Aura\Router\Map $router) {
    

    $router->get('foo', '/asdf/{bar}', 'FooController@index');

    $router->get('test', '/test', function($request, $response) {

        var_dump( $this );
        var_dump( $request );
        var_dump( $response );
        $response->getBody()->write('This is test!');

        return $response;        
    });

};