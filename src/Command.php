<?php
declare(strict_types=1);

namespace Wex;

abstract class Command
{
    const COLOR_RED     = "\33[0;31m";
    const COLOR_GREEN   = "\33[0;32m";
    const COLOR_RESET   = "\33[0m";

    public static function success($msg)
    {
        $message = sprintf("%s%s%s\n",
            static::COLOR_GREEN,
            call_user_func_array('sprintf', func_get_args()),
            static::COLOR_RESET
        );

        echo $message;
    }

    public static function error($msg)
    {
        $message = sprintf("%s%s%s\n",
            static::COLOR_RED,
            call_user_func_array('sprintf', func_get_args()),
            static::COLOR_RESET
        );

        echo $message;
    }

    public static function parseArgs($args)
    {
        $out = [];

        for ($i = 0; $i < count($args); $i++) {
            $now    = $args[$i];
            $next   = $args[$i + 1] ?? false;
            $value  = true;

            if (strpos($now, '=') === false) {
                if ($next && $next[0] !== '-') {
                    $value = $next;
                    $i++;
                } else {

                }
            } else {
                list($now, $value) = explode('=', $now);
            }

            $out[ preg_replace('/^-+/', '', $now) ] = $value;
        }

        return $out;
    }

    public function __construct(array $parameters = [])
    {
        $data = static::parseArgs($parameters);

        $errors     = [];
        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getDefaultProperties() as $name => $value) {
            $newValue = $data[$name] ?? false;
            if (is_null($value)) {
                if (!$newValue) {
                    $errors[$name]  = $name;
                } else {
                    $this->$name    = $newValue;
                }
            } else {
                $this->$name        = $newValue ?? $value;
            }
        }

        if (count($errors)) {
            throw new \Exception(sprintf('Missing required parameters: %s', implode(', ', $errors)));
        }
    }

    public abstract function __invoke();

    public static function getClass($name) : string
    {
        $class = ucfirst(strtolower($name ?? 'default'));

        return "{$class}Command";
    }

    public static function autoload(string $class) : void
    {
        $commandPath = __ROOT__ . '/app/cli';
        $commandFile = $commandPath . "/{$class}.php";

        @include_once $commandFile;
    }


}