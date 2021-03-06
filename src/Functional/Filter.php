<?php
namespace Haystack\Functional;

use Haystack\HArray;

class Filter
{
    /** @var array */
    private $arr;

    public function __construct(HArray $array)
    {
        $this->arr = $array->toArray();
    }

    public function filter(callable $func = null, $flag = null)
    {
        // Default
        if ($func === null) {
            $filtered = new FilterWithDefaults($this->arr);
            return $filtered->filter();
        }

        // No flags are passed
        if ($flag === null) {
            $filtered = new FilterWithValue($this->arr);
            return $filtered->filter($func);
        }

        // Flag of "USE_KEY" is passed
        if ('key' === $flag) {
            $filtered = new FilterWithKey($this->arr);
            return $filtered->filter($func);
        }

        // Flag of "BOTH" is passed
        if ('both' === $flag) {
            $filtered = new FilterWithValueAndKey($this->arr);
            return $filtered->filter($func);
        }

        throw new \InvalidArgumentException('Invalid flag name');
    }
}
