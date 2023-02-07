<?php

namespace RobinTheHood\ModifiedStdModule\Classes;

class Configuration
{
    private $prefix;

    public function __construct($prefix)
    {
        $this->prefix = $prefix;

        $constants = $this->filterConstants($prefix);

        $this->defineVariablesFromContants($constants, $prefix);
    }

    public function __get($key)
    {
        if (isset($this->$key)) {
            return $this->$key;
        }

        throw new \RuntimeException("Unknown configuration variable " . $key);
    }

    private function filterConstants($prefix)
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

    private function defineVariablesFromContants($constants, $prefix)
    {
        foreach ($constants as $key => $value) {
            $var = $this->removePrefix($key, $prefix);
            $var = CaseConverter::screamingToCamel($var);

            $this->$var = $value;
        }
    }

    private function removePrefix($string, $prefix)
    {
        return str_replace($prefix, '', $string);
    }

    public function screamingCaseToCamelCase($string)
    {
        trigger_error(
            sprintf(
                /** TRANSLATORS: %1$s: Old method name. %2$s: New method name.*/
                'Using the %1$s method is deprecated. Use %2$s instead.',
                __METHOD__,
                'CaseConverter::screamingToCamel'
            ),
            E_USER_DEPRECATED
        );

        return CaseConverter::screamingToCamel($string);
    }

    public function screamingCaseToLispCase(string $string)
    {
        trigger_error(
            sprintf(
                /** TRANSLATORS: %1$s: Old method name. %2$s: New method name.*/
                'Using the %1$s method is deprecated. Use %2$s instead.',
                __METHOD__,
                'CaseConverter::screamingToLisp'
            ),
            E_USER_DEPRECATED
        );

        return CaseConverter::screamingToLisp($string);
    }
}
