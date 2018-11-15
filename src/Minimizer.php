<?php
declare(strict_types=1);

namespace Wex;

use Leafo\ScssPhp\Compiler;
use MatthiasMullie\Minify;

abstract class Minimizer
{
    public static function js(array $files, string $filename = 'app.js')
    {
        $isChanged  = App::$debug;
        $sourcePath = __ROOT__ . '/app/media/js';
        $targetFile = __ROOT__ . "/public/js/{$filename}";
        $lastModify = file_exists($targetFile) ? filemtime($targetFile) : 0;

        if (!App::$debug) {
            foreach ($files as $file) {
                $fileModify = filemtime("{$sourcePath}/{$file}");
                if ($fileModify !== false && $fileModify > $lastModify) {
                    $isChanged = true;
                    break;
                }
            }
        }

        if ($isChanged) {
            $js = '';
            foreach ($files as $file) {
                $fileName = "{$sourcePath}/{$file}";
                if (!file_exists($fileName)) continue;
                $js .= file_get_contents($fileName);                
            }

            if (!App::$debug) {
                $minifier = new Minify\JS;
                $minifier->add($js);

                $js = $minifier->minify();
            }

            file_put_contents($targetFile, $js);
        }

        return "/js/{$filename}";
    }

    public static function css(array $files, string $filename = 'app.css')
    {
        $isChanged  = App::$debug;
        $sourcePath = __ROOT__ . '/app/media/scss';
        $targetFile = __ROOT__ . "/public/css/{$filename}";
        $lastModify = file_exists($targetFile) ? filemtime($targetFile) : 0;

        if (!App::$debug) {
            foreach ($files as $file) {
                $fileModify = filemtime("{$sourcePath}/{$file}");
                if ($fileModify !== false && $fileModify > $lastModify) {
                    $isChanged = true;
                    break;
                }
            }
        }

        if ($isChanged) {
            $css = '';
            foreach ($files as $file) {
                $fileName = "{$sourcePath}/{$file}";
                if (!file_exists($fileName)) continue;
                $css .= file_get_contents($fileName);                
            }

            $compiler = new Compiler;
            $compiler->setImportPaths($sourcePath);
            if (App::$debug) {
                $compiler->setFormatter('Leafo\ScssPhp\Formatter\Expanded');
            } else {
                $compiler->setFormatter('Leafo\ScssPhp\Formatter\Crunched');
            }

            $css = $compiler->compile($css, $sourcePath);

            if (!App::$debug) {
                $minifier = new Minify\CSS;
                $minifier->add($css);

                $css = $minifier->minify();
            }

            file_put_contents($targetFile, $css);
        }

        return "/css/{$filename}";
    }
}