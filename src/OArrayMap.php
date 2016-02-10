<?php
namespace OPHP;

class OArrayMap
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
     * @return array
     */
    public function map(callable $func)
    {
        return array_map($func, $this->arr);
    }
}
