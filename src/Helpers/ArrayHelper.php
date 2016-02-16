<?php
namespace OPHP\Helpers;

use OPHP\OString;

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

    /**
     * @param $thing
     * @return bool
     */
    public static function canBeInArray($thing)
    {
        $possibility = is_array($thing)
            || is_scalar($thing)
            || $thing instanceof \ArrayObject
            || $thing instanceof OString;

        return $possibility;
    }
}
