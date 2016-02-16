<?php
namespace OPHP\Container;

use OPHP\Helpers\Helper;
use OPHP\OArray;

class OArrayRemove
{
    private $helper;
    private $arr;

    public function __construct(OArray $array)
    {
        $this->helper = new Helper();
        $this->arr = $array;
    }

    public function remove($value)
    {
        if ($this->helper->canBeInArray($value)) {
            if (false === $this->arr->contains($value)) {
                return $this->arr;
            }

            $newArr = $this->arr->toArray();
            $key = $this->arr->locate($value);
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} cannot be contained within an OArray");
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
