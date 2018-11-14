<?php
declare(strict_types=1);

namespace Wex\Controller;

class PhpResponse extends Response
{
    public function render(string $viewPath, string $viewFile, string $layout, array $data)
    {
        $_template  = __ROOT__ . "/app/views/{$viewPath}/{$viewFile}.phtml";
        $_layout    = __ROOT__ . "/app/views/{$layout}.phtml";

        if (!file_exists($_template)) throw new \Exception("View {$_template} not found");
        if (!file_exists($_layout)) throw new \Exception("Layout {$_layout} not found");

        $renderer = function($___filename, $___data) {
            ob_start();

            extract($___data, EXTR_OVERWRITE);
            unset( $___data );

            require_once $___filename;
            $html = ob_get_contents();

            ob_end_clean();

            return $html;
        };

        $body = $renderer($_template, $data);
        $html = $renderer($_layout, ['__CONTENT' => $body]);

        return $html;
    }
}
