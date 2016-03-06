<?php
namespace Haystack\Helpers;

use Haystack\HString;

class Helper
{
    public static function getType($thing)
    {
        $type = gettype($thing);
        if ('object' === $type) {
            $type = get_class($thing);
        }

        return $type;
    }

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
            || $thing instanceof HString;

        return $possibility;
    }
}
