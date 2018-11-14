<?php
declare(strict_types=1);

namespace Wex\Controller;

abstract class ResponseFactory
{
    public static function getResponse($format) : Response
    {
        $className      = ucfirst(strtolower($format)) . 'Response';
        $fullClassName  = "Wex\\Controller\\{$className}";

        $instance       = new $fullClassName;

        return $instance;
    }
}