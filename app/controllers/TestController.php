<?php

use Wex\App\HttpException;

class TestController extends \Wex\Controller
{
    public function view($parameters)
    {
        $page   = new \Wex\Page([
            'type'  => 'template',
            'value' => 'frontpage',
        ]);

        if (!$page) {
            throw new HttpException("Page '{$uri}' could not be found.", 404);
        }

        return $page;
    }

}