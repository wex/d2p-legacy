<?php
declare(strict_types=1);

namespace Wex\Controller;

use Zend\Diactoros\Response as ZendResponse;

abstract class Response extends ZendResponse
{
    public abstract function configure();

    public abstract function run(string $viewPath, string $viewFile) : Response;

    public static function factoryResponse($format) : Response
    {
        $className      = ucfirst(strtolower($format)) . 'Response';
        $fullClassName  = "Wex\\Controller\\{$className}";

        $instance       = new $fullClassName;
        $instance->configure();

        return $instance;
    }
}