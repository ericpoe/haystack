<?php
namespace OPHP\Functional;

class OArrayWalk
{
    /**
     * @param array &$arr
     * @param callable $func
     */
    static public function walk(array &$arr, callable $func)
    {
        array_walk($arr, $func);
    }
}
