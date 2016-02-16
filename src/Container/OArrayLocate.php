<?php
namespace OPHP\Container;

use OPHP\OArray;

class OArrayLocate
{
    /** @var OArray */
    private $arr;

    public function __construct(OArray $arr)
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
