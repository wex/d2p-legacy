<?php
declare(strict_types=1);

namespace Wex\Page;

use Wex\ActiveRecord;
use Wex\ActiveRecord\Blueprint;

class Html extends ActiveRecord
{
    const table = 'pages_html';

    protected function describe(Blueprint &$blueprint) : void
    {
        $blueprint->parent('page_id', 'Wex\Page')->required();
        $blueprint->string('key')->required();
        $blueprint->string('value')->max(128000);
    }

}