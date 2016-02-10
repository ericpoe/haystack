<?php
namespace OPHP;

class OArrayMap
{
    private $arr;

    public function __construct(OArray $array)
    {
        $this->arr = $array->toArray();
    }

    public function map(callable $func)
    {
        return array_map($func, $this->arr);
    }
}
