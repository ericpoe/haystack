<?php
namespace OPHP\Functional;

class OArrayWalk
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
