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
     * @throws ElementNotFoundException
     */
    public function locate($value)
    {
        if ($this->arr->contains($value)) {
            return array_search($value, $this->arr->toArray());
        }

        throw new ElementNotFoundException($value);
    }
}
