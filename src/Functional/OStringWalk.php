<?php
namespace OPHP\Functional;

use OPHP\OString;

class OStringWalk
{
    /**
     * @param OString &$string
     * @param callable $func
     */
    public static function walk(OString &$string, callable $func)
    {
        $size = $string->count();

        for ($i = 0; $i < $size; $i++) {
            $string[$i] = $func($string[$i], $i);
        }
    }
}
