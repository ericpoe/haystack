<?php
namespace Haystack\Container;

use Haystack\Helpers\ArrayHelper;
use Haystack\Helpers\Helper;
use Haystack\HArray;
use Haystack\HString;

class HaystackArrayInsert
{
    private $helper;
    private $arr;

    public function __construct(HArray $array)
    {
        $this->helper = new Helper();
        $this->arr = $array;
    }

    public function insert($value, $key)
    {
        if ($value instanceof HArray) {
            $valueArray = $value->toArray();
        } elseif ($value instanceof HString) {
            $valueArray = $value->toString();
        } elseif (ArrayHelper::canBeInArray($value)) {
            $valueArray = $value;
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} cannot be contained within an HArray");
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

        return array($array, $length);
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

        return array($array, $length);
    }

    /**
     * @param $value
     * @return array
     */
    private function setSubarrayAndLengthWhenNoKeyProvided($value)
    {
        $array = $value;
        $length = sizeof($this->arr);

        return array($array, $length);
    }
}
