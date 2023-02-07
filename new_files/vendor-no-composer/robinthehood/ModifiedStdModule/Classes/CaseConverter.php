<?php

namespace RobinTheHood\ModifiedStdModule\Classes;

class CaseConverter
{
    /**
     * Convert SCREAMING_SNAKE_CASE to camelCase.
     *
     * @param string $screamingString The SCREAMING_SNAKE_CASE to convert.
     *
     * @return string
     */
    public static function screamingToCamel(string $screamingString): string
    {
        $screamingParts = explode('_', $screamingString);
        $camelParts = [];
        $camelString = '';

        foreach ($screamingParts as $screamingPart) {
            $camelPart = $screamingPart;
            $camelPart = strtolower($camelPart);
            $camelPart = ucfirst($camelPart);

            $camelParts[] = $camelPart;
        }

        $camelString = implode('', $camelParts);
        $camelString = lcfirst($camelString);

        return $camelString;
    }

    /**
     * Convert SCREAMING_SNAKE_CASE to lisp-case.
     *
     * @param string $screamingString The SCREAMING_SNAKE_CASE to convert.
     *
     * @return string
     */
    public static function screamingToLisp(string $screamingString): string
    {
        $screamingParts = explode('_', $screamingString);
        $lispParts = [];
        $lispString = '';

        foreach ($screamingParts as $screamingPart) {
            $lispPart = strtolower($screamingPart);

            $lispParts[] = $lispPart;
        }

        $lispString = implode('-', $lispParts);

        return $lispString;
    }
}
