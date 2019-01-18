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

        foreach ($hString as $i => $iValue) {
            $hString[$i] = $func($hString[$i], $i);
        }
    }
}
