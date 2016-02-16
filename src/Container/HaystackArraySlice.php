<?php
namespace Haystack\Container;

use Haystack\HArray;

class HaystackArraySlice
{
    private $arr;

    /**
     * @param HArray $array
     */
    public function __construct(HArray $array)
    {
        $this->arr = $array->toArray();
    }

    /**
     * @param $start
     * @param null $length
     * @return array
     */
    public function slice($start, $length = null)
    {
        if (is_null($start) || !is_numeric($start)) {
            throw new \InvalidArgumentException("Slice parameter 1, \$start, must be an integer");
        }

        if (!is_null($length) && !is_numeric($length)) {
            throw new \InvalidArgumentException("Slice parameter 2, \$length, must be null or an integer");
        }

        $maintainIndices = false;

        return array_slice($this->arr, $start, $length, $maintainIndices);
    }

}
