<?php
namespace Haystack\Container;

use Haystack\HArray;

class HaystackArrayLocate
{
    /** @var HArray */
    private $arr;

    public function __construct(HArray $arr)
    {
        $this->arr = $arr;
    }

    /**
     * @param $value
     * @return int|string
     */
    public function locate($value)
    {
        if ($this->arr->contains($value)) {
            $key = array_search($value, $this->arr->toArray());
        } else {
            $key = -1;
        }

        return $key;
    }
}
