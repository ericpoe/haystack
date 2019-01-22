<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringWalk
{
    public static function walk(HString $hString, callable $func)
    {
        foreach ($hString as $i => $iValue) {
            $hString[$i] = $func($hString[$i], $i);
        }
    }
}
