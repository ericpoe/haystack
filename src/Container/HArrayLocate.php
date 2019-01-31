<?php
namespace Haystack\Container;

use Haystack\HArray;

class HArrayLocate
{
    /** @var HArray */
    private $arr;

    public function __construct(HArray $arr)
    {
        $this->arr = $arr;
    }

    /**
     * @param mixed $value
     * @return int|string
     * @throws ElementNotFoundException
     */
    public function locate($value)
    {
        if ($this->arr->contains($value)) {
            return array_search($value, $this->arr->toArray(), true);
        }

        throw new ElementNotFoundException($value);
    }
}
