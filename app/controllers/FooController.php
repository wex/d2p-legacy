<?php

class FooController extends \Wex\Controller
{
    public function index($bar = null)
    {
        try {
            $this->var = Wex\Page::load((int) $bar);
        } catch (\Exception $e) {
            $this->var = 'ei löytynyt';
        }
    }

    public function barAction()
    {
        $this->foo = 'sng';
    }
}