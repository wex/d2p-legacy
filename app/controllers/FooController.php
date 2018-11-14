<?php

class FooController extends \Wex\Controller
{
    public function index($bar = null)
    {
        $this->var = Wex\Page::load((int) $bar);
    }

    public function barAction()
    {
        $this->foo = 'sng';
    }
}