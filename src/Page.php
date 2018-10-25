<?php
declare(strict_types=1);

namespace Wex;

use Wex\ActiveRecord\Blueprint;
use Wex\ActiveRecord\Exception\NotFound;

class Page extends ActiveRecord implements ActiveRecord\SoftDelete, ActiveRecord\Timestamps
{
    const   table   = 'pages';

    protected function describe(Blueprint &$blueprint) : void
    {
        $blueprint->string('uri')->unique()->required();
        $blueprint->timestamp('published_at');
    }

    public static function get(string $uri)
    {
        try {
            return static::select()->where('uri = ?', $uri)->first();
        } catch (NotFound $e) {
            return false;
        }
    }
}