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
        $camelParts = array_map(
            $screamingParts,
            function (string $camelPart) {
                $camelPart = strtolower($camelPart);
                $camelPart = ucfirst($camelPart);

                return $camelPart;
            }
        );
        $camelString = implode('', $camelParts);

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
        $lispParts = array_map(
            $screamingParts,
            function (string $lispPart) {
                $lispPart = strtolower($lispPart);

                return $lispPart;
            }
        );
        $lispString = implode('-', $lispParts);

        return $lispString;
    }
}
