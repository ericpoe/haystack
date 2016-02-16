<?php
namespace OPHP\Functional;

use OPHP\OArray;

class OArrayFilter
{
    /** @var array */
    private $arr;

    public function __construct(OArray $array)
    {
        $this->arr = $array->toArray();
    }

    public function filter(callable $func = null, $flag = null)
    {
        // Default
        if (is_null($func)) {
            $filtered = new OArrayFilterWithDefaults($this->arr);
            return $filtered->filter();
        }

        // No flags are passed
        if (is_null($flag)) {
            $filtered = new OArrayFilterWithValue($this->arr);
            return $filtered->filter($func);
        }

        // Flags are USE_KEY or USE_BOTH
        if ("key" === $flag || "both" === $flag) {
            // Flag of "USE_KEY" is passed
            if ("key" === $flag) {
                $filtered = new OArrayFilterWithKey($this->arr);
                return $filtered->filter($func);
            }
            // Flag of "USE_BOTH is passed
            $filtered = new OArrayFilterWithValueAndKey($this->arr);
            return $filtered->filter($func);
        }
        throw new \InvalidArgumentException("Invalid flag name");
    }
}