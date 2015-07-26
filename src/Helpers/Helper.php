<?php
namespace OPHP\Helpers;

use OPHP\OString;

class Helper
{
    public function getType($thing)
    {
        $type = gettype($thing);
        if ('object' === $type) {
            $type = get_class($thing);
        }

        return $type;
    }

    /**
     * @param $thing
     * @return bool
     */
    public function canBeInArray($thing)
    {
        $possibility = is_array($thing)
            || is_scalar($thing)
            || $thing instanceof \ArrayObject
            || $thing instanceof OString;

        return $possibility;
    }
}
