<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
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
        if (Helper::canBeInArray($value)) {
            $arr = $this->arr->toArray();
            $answer = in_array($value, $arr);
        } else {
            throw new \InvalidArgumentException(sprintf("%s cannot be contained within an HArray", Helper::getType($value)));
        }
        return $answer;
    }
}
