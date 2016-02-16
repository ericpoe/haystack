<?php
namespace Haystack\Container;

use Haystack\Helpers\ArrayHelper;
use Haystack\Helpers\Helper;
use Haystack\HArray;

class HaystackArrayRemove
{
    private $helper;
    private $arr;

    public function __construct(HArray $array)
    {
        $this->helper = new Helper();
        $this->arr = $array;
    }

    public function remove($value)
    {
        if (ArrayHelper::canBeInArray($value)) {
            if (false === $this->arr->contains($value)) {
                return $this->arr;
            }

            $newArr = $this->arr->toArray();
            $key = $this->arr->locate($value);
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} cannot be contained within an HArray");
        }

        if (is_numeric($key)) {
            unset($newArr[$key]);

            return array_values($newArr);
        }

        // key is string
        unset($newArr[$key]);

        return $newArr;
    }
}
