<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringWalk
{
    /**
     * @param HString &$hString
     * @param callable $func
     */
    public static function walk(HString &$hString, callable $func)
    {
        $size = $hString->count();

        for ($i = 0; $i < $size; $i++) {
            $hString[$i] = $func($hString[$i], $i);
        }
    }
}
