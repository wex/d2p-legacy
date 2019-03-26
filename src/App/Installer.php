<?php
declare(strict_types=1);

namespace Wex\App;

use Composer\Script\Event;
use Zend\Config\Reader\Ini as IniReader;
use Zend\Config\Writer\Ini as IniWriter;
use Zend\Config\Config;
use Zend\Db\Adapter\Adapter;
use Wex\App;

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
            
            echo "\n";
            printf("Next you should check our project root for .config file.\n");
            printf("Update your database credentials and after that run:\n\n");
            printf("composer update\n");
            printf("composer setup\n\n");
            printf("After this, you should update keen's permissions:\n");
            printf("chmod +x keen\n");
        }

        return 1;
    }

    public static function postUpdate(Event $event)
    {
        return 1;
    }

    public static function installer()
    {
        define('__ROOT__', getcwd());

        $iniReader  = new IniReader;
        $config     = new Config($iniReader->fromFile('.config'), true);
        $db         = new Adapter( $config->database->toArray() );

        try {
            $db->query('SELECT 1', Adapter::QUERY_MODE_EXECUTE);
        } catch (\Exception $e) {
            printf("Check your .config and make sure your database configuration is ok!\n");
            return 0;
        }

        foreach (glob(__ROOT__ . '/sql/install/*.sql') as $file) {
            $table = substr(basename($file), 0, -4);
            printf("\t%s\n", $table);
            $db->query(file_get_contents($file), Adapter::QUERY_MODE_EXECUTE);
        }
        
        printf("\nDone!");

        return 1;
    }

    public static function createTable(Event $event)
    {
        define('__ROOT__', getcwd());

        App::bootstrap();

        $arguments = $event->getArguments();

        foreach ($arguments as $className) {
            printf("\t%s: ", $className);
            $sql = $className::createSql();
            try {
                App::$db->query($sql, Adapter::QUERY_MODE_EXECUTE);
                printf("Done.\n");
            } catch (\Exception $e) {
                printf("Failed (%s)\n", $e->getMessage());
            }
        }
    }
}
