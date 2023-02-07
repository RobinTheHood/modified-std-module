<?php

namespace RobinTheHood\ModifiedStdModule\Classes;

class Configuration
{
    private $prefix;

    private static function filterConstants($prefix)
    {
        $result = [];

        $constants = get_defined_constants();
        foreach ($constants as $key => $constantValue) {
            if (\strpos($key, $prefix) !== 0) {
                continue;
            }

            $result[$key] = $constantValue;
        }

        return $result;
    }

    private static function removePrefix($string, $prefix)
    {
        return str_replace($prefix, '', $string);
    }

    public static function screamingCaseToCamelCase($string)
    {
        $parts = explode('_', $string);

        foreach ($parts as &$part) {
            if (!$part) {
                continue;
            }

            $part = strtolower($part);
            $part = ucfirst($part);
        }

        $string = implode('', $parts);
        $string = lcfirst($string);
        return $string;
    }

    public static function screamingCaseToLispCase(string $string)
    {
        $parts = explode('_', $string);

        foreach ($parts as &$part) {
            if (!$part) {
                continue;
            }

            $part = strtolower($part);
        }

        $string = implode('-', $parts);

        return $string;
    }

    public function __construct($prefix)
    {
        $this->prefix = $prefix;

        $constants = self::filterConstants($prefix);

        $this->defineVariablesFromContants($constants, $prefix);
    }

    public function __get($key)
    {
        if (isset($this->$key)) {
            return $this->$key;
        }

        throw new \RuntimeException("Unknown configuration variable " . $key);
    }

    private function defineVariablesFromContants($constants, $prefix)
    {
        foreach ($constants as $key => $value) {
            $var = self::removePrefix($key, $prefix);
            $var = self::screamingCaseToCamelCase($var);

            $this->$var = $value;
        }
    }
}
