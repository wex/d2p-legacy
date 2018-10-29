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
        $blueprint->string('uri')->unique()->required()->index('unique');
        $blueprint->string('state')->default('hidden')->options(['published', 'hidden', 'deleted'])->index();;
        $blueprint->string('type')->options(['template', 'redirect', 'collection']);
        $blueprint->string('value');
        $blueprint->string('lang')->max(8)->index();
        $blueprint->integer('rank')->index()->min(0);

        $blueprint->hasMany('html', 'Wex\Page\Html');
        
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