<?php
namespace Haystack\Functional;

class HArrayWalk
{
    public static function walk(array $arr, callable $func)
    {
        array_walk($arr, $func);
    }
}
