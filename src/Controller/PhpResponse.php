<?php
declare(strict_types=1);

namespace Wex\Controller;

use Wex\Controller;


class PhpResponse extends Response
{
    protected   $template;
    protected   $layout;

    public function run(string $viewPath, string $viewFile, string $layout = null) : Response
    {
        $this->template = __ROOT__ . '/app/views/' . $viewPath . '/' . $viewFile . '.phtml';
        $this->layout   = __ROOT__ . '/app/views/' . ($layout ?? 'default') . '.phtml';

        if (!file_exists($this->template)) throw new \Exception("View {$this->template} not found");
        if (!file_exists($this->layout)) throw new \Exception("Layout {$this->layout} not found");

        return $this;
    }

    public function configure()
    {
        
    }

    public function render()
    {
        $renderer = function($filename, $data) {
            ob_start();

            extract( $data, EXTR_OVERWRITE );
            require_once $filename;

            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        };

        $body = $renderer($this->template, $this->data);
        $html = $renderer($this->layout, ['__CONTENT' => $body]);

        $this->getBody()->write($html);

        return $this;
    }
}
