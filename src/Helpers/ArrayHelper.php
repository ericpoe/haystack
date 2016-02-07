<?php
namespace OPHP\Helpers;

class ArrayHelper
{
    /**
     * Determines if an array is associative or not
     *
     * @link http://stackoverflow.com/questions/173400
     * @param array $array
     * @return bool
     */
    public static function isAssociativeArray(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}
