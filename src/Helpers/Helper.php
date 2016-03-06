<?php
namespace Haystack\Helpers;

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
}
