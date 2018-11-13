<?php

class FooController extends \Wex\Controller
{
    public function index($bar = null)
    {
        $this->var = $bar;
    }

    public function barAction()
    {
        $this->foo = 'sng';
    }
}