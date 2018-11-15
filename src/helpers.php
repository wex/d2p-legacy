<?php

use \Wex\Minimizer;

function css(array $files, string $filename = 'app.css')
{
    return Minimizer::css($files, $filename);
}

function js(array $files, string $filename = 'app.js')
{
    return Minimizer::js($files, $filename);
}