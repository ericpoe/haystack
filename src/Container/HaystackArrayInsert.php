<?php
namespace Haystack\Container;

use Haystack\HArray;
use Haystack\Helpers\Helper;
use Haystack\HString;

class HaystackArrayInsert
{
    private $arr;

    public function __construct(HArray $array)
    {
        $this->arr = $array;
    }

    public function insert($value, $key)
    {
        if ($value instanceof HArray) {
            $valueArray = $value->toArray();
        } elseif ($value instanceof HString) {
            $valueArray = $value->toString();
        } elseif (Helper::canBeInArray($value)) {
            $valueArray = $value;
        } else {
            $valueArray = [$value];
        }

        if (isset($key)) {
            if (is_numeric($key)) {
                list($array, $length) = $this->setSubarrayAndLengthForSequentialArray($key, $valueArray);
            } elseif (is_string($key)) {
                list($array, $length) = $this->setSubarrayAndLengthForAssociativeArray($key, $valueArray);
            } else {
                throw new \InvalidArgumentException("Invalid array key");
            }
        } else {
            list($array, $length) = $this->setSubarrayAndLengthWhenNoKeyProvided($valueArray);
        }

        $first = $this->arr->slice(0, $length)->toArray();
        $lastStartingPoint = sizeof($this->arr) - sizeof($first);
        $last = $this->arr->slice($length, $lastStartingPoint)->toArray();

        return new HArray(array_merge_recursive($first, (array) $array, $last));
    }

    /**
     * @param $key
     * @param $value
     * @return array
     */
    private function setSubarrayAndLengthForSequentialArray($key, $value)
    {
        $array = $value;
        $length = (int) $key;

        return [$array, $length];
    }

    /**
     * @param string $key
     * @param        $value
     * @return array
     */
    private function setSubarrayAndLengthForAssociativeArray($key, $value)
    {
        $array = [$key => $value];
        $length = sizeof($this->arr);

        return [$array, $length];
    }

    /**
     * @param $value
     * @return array
     */
    private function setSubarrayAndLengthWhenNoKeyProvided($value)
    {
        $array = $value;
        $length = sizeof($this->arr);

        return [$array, $length];
    }
}
