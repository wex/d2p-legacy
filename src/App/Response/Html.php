<?php

namespace Wex\App\Response;

use Wex\App\Response;

class Html implements Response
{
    public function __toString()
    {


        return '<b>Happy <u>Man</u></b>';
    }
}