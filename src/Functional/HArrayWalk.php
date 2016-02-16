<?php
namespace Haystack\Functional;

class HArrayWalk
{
    /**
     * @param array &$arr
     * @param callable $func
     */
    public static function walk(array &$arr, callable $func)
    {
        array_walk($arr, $func);
    }
}
