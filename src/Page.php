<?php
declare(strict_types=1);

namespace Wex;

use Wex\ActiveRecord\Blueprint;

class Page extends ActiveRecord implements ActiveRecord\SoftDelete, ActiveRecord\Timestamps
{
    const   table   = 'pages';

    protected function describe(Blueprint &$blueprint) : void
    {
        $blueprint->string('uri')->unique()->required();
        $blueprint->timestamp('published_at');
    }
}