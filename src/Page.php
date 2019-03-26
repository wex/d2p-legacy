<?php
declare(strict_types=1);

namespace Wex;

use Wex\ActiveRecord\Blueprint;
use Wex\ActiveRecord\Exception\NotFound;
use Wex\Base\Renderable;
use Wex\Controller\Session;

class Page extends ActiveRecord implements Renderable, ActiveRecord\SoftDelete, ActiveRecord\Timestamps
{
    const   table   = 'pages';

    protected   $session    = null;

    protected function describe(Blueprint &$blueprint) : void
    {
        $blueprint->string('uri')->unique()->required()->index('unique');
        $blueprint->string('state')->default('hidden')->options(['published', 'hidden', 'deleted'])->index();;
        $blueprint->string('type')->options(['template', 'redirect', 'collection']);
        $blueprint->string('value');
        $blueprint->string('lang')->max(8)->index();
        $blueprint->integer('rank')->index()->min(0);
        $blueprint->timestamp('published_at');
    }

    protected function relatedTo() : void
    {
        $this->hasMany(\Wex\Page\Html::class, 'fields', 'page_id');
        $this->hasMany(\Wex\Page\Html::class, 'users', 'page_id')->thru('pages2users', 'user_id');
    }

    public static function getByUri(string $uri)
    {
        try {
            return static::select()->where('uri = ?', $uri)->first();
        } catch (NotFound $e) {
            return false;
        }
    }
    
    public function render()
    {
        $renderer = function($__filename) {
            ob_start();

            require $__filename;

            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        };

        $filename = __ROOT__ . "/app/templates/{$this->value}.php";

        $oldPath = \get_include_path();
        \set_include_path(implode(PATH_SEPARATOR, [
            $oldPath,
            __ROOT__ . '/app/views/include',
            __ROOT__ . '/app/templates/include',
        ]));

        $html = $renderer($filename);

        \set_include_path($oldPath);

        return $html;
    }
    
    public function session()
    {
        if (null === $this->session) {
            $this->session = new Session;
        }

        return $this->session;
    }

    public function get($param)
    {
        return filter_input(INPUT_GET, $param);
    }

    public function post($param)
    {
        return filter_input(INPUT_POST, $param);
    }
}