<?php
namespace OPHP\Helpers;

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
}
