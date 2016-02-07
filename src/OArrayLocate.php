<?php
namespace OPHP;

class OArrayLocate
{
    /** @var OArray */
    private $arr;

    public function __construct(OArray $arr)
    {
        $this->arr = $arr;
    }

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
