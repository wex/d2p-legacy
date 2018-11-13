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
                'debug'     => true,
                'optimizations' => 0,
            ]);
        }
    }

    public function run(string $viewPath, string $viewFile, string $layout = null) : Response
    {
        try {
            $this->template = static::$twig->load("{$viewPath}/{$viewFile}.twig");
        } catch (\Twig_Error $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    public function render()
    {
        $this->getBody()->write( $this->template->render( $this->data ) );

        return $this;
    }
}