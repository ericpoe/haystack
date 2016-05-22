<?php
namespace Haystack\Container;

use Haystack\HArray;

class HArrayRemove
{
    /** @var HArray */
    private $arr;

    public function __construct(HArray $array)
    {
        $this->arr = $array;
    }

    public function remove($value)
    {
        if (false === $this->arr->contains($value)) {
            return $this->arr;
        }

        $newArr = $this->arr->toArray();
        $key = $this->arr->locate($value);
        unset($newArr[$key]);

        if ($this->allKeysNumeric(array_keys($newArr))) {
            return array_values($newArr);
        }

        return $newArr;
    }

    private function allKeysNumeric(array $keys)
    {
        return sizeof($keys) === sizeof(array_filter($keys, function ($key) {
            return is_numeric($key);
        }));
    }
}
