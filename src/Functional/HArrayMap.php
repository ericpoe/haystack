<?php
namespace Haystack\Functional;

use Haystack\HArray;

class HArrayMap
{
    /** @var array */
    private $arr;

    /**
     * @param HArray $array
     */
    public function __construct(HArray $array)
    {
        $this->arr = $array->toArray();
    }

    /**
     * @param callable $func
     * @return array
     */
    public function map(callable $func)
    {
        return array_map($func, $this->arr);
    }
}
