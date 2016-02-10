<?php
namespace OPHP;

class OArrayReduce
{
    /** @var array */
    private $arr;

    /**
     * @param OArray $array
     */
    public function __construct(OArray $array)
    {
        $this->arr = $array->toArray();
    }

    /**
     * @param callable $func
     * @param $initial
     * @return bool|float|int|OString|OArray
     */
    public function reduce(callable $func, $initial)
    {
        $reduced = array_reduce($this->arr, $func, $initial);

        if ($reduced instanceof \ArrayObject || is_array($reduced)) {
            return new OArray($reduced);
        }

        if (is_string($reduced)) {
            return new OString($reduced);
        }

        return $reduced;
    }
}
