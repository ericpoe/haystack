<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringWalk
{
    /**
     * @param HString &$string
     * @param callable $func
     */
    public static function walk(HString &$string, callable $func)
    {
        $size = $string->count();

        for ($i = 0; $i < $size; $i++) {
            $string[$i] = $func($string[$i], $i);
        }
    }
}
