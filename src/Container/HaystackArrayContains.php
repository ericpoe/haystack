<?php
namespace Haystack\Container;

use Haystack\HArray;

class HaystackArrayContains
{
    /**
     * @var HArray
     */
    private $arr;

    public function __construct(HArray $arr)
    {
        $this->arr = $arr;
    }

    /**
     * @param $value
     * @return bool
     */
    public function contains($value)
    {
        return in_array($value, $this->arr->toArray());
    }
}
