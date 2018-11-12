<?php
declare(strict_types=1);

namespace Wex\Controller;

class TwigResponse extends Response
{
    static      $twig       = null;
    static      $loader     = null;
    protected   $template;

    public function configure()
    {
        if (is_null(static::$loader) || is_null(static::$twig)) {
            static::$loader = new \Twig_Loader_Filesystem(__ROOT__ . '/app/views');
            static::$twig   = new \Twig_Environment(static::$loader, [
                'cache'     => __ROOT__ . '/storage/cache/views',
            ]);
        }
    }

    public function run(string $viewPath, string $viewFile) : Response
    {
        $this->template = static::$twig->load("{$viewPath}/{$viewFile}.twig");

        return $this;
    }
}