<?php
namespace Haystack\Functional;

use Haystack\HArray;
use Haystack\HString;

class HArrayReduce
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
     * @param $initial
     * @return bool|float|int|HString|HArray
     */
    public function reduce(callable $func, $initial)
    {
        $reduced = array_reduce($this->arr, $func, $initial);

        if ($reduced instanceof \ArrayObject || is_array($reduced)) {
            return new HArray($reduced);
        }

        if (is_string($reduced)) {
            return new HString($reduced);
        }

        return $reduced;
    }
}
