<?php

class FooController extends \Wex\Controller
{
    public function index($bar = null)
    {
        var_dump($bar);
    }
}