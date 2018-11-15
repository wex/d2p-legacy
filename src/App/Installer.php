<?php
declare(strict_types=1);

namespace Wex\App;

use Composer\Script\Event;
use Zend\Config\Reader\Ini as IniReader;
use Zend\Config\Writer\Ini as IniWriter;
use Zend\Config\Config;

class Installer
{
    public static function createProject(Event $event)
    {
        if (!file_exists('.config')) {
            $iniReader  = new IniReader;
            $config     = new Config($iniReader->fromFile('.config-example'), true);

            $config->app->key = base64_encode(openssl_random_pseudo_bytes(32));

            $iniWriter  = new IniWriter;
            $iniWriter->setRenderWithoutSectionsFlags(true);
            $iniWriter->toFile('.config', $config);
        }

        return 1;
    }

    public static function postUpdate(Event $event)
    {
        return 1;
    }
}
