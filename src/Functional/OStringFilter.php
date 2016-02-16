<?php
namespace OPHP\Functional;

use OPHP\OString;

class OStringFilter
{
    /** @var OString */
    protected $string;

    public function __construct(OString $string)
    {
        $this->string = $string;
    }
    public function filter(callable $func = null, $flag = null)
    {
        // Default
        if (is_null($func)) {
            $filtered = new OStringFilterWithDefaults($this->string);
            return $filtered->filter();
        }

        // No flags are passed
        if (is_null($flag)) {
            $filtered = new OStringFilterWithValue($this->string);
            return $filtered->filter($func);
        }

        if ("key" === $flag || "both" === $flag) {
            // Flag of "USE_KEY" is passed
            if ("key" === $flag) {
                $filtered = new OStringFilterWithKey($this->string);
                return $filtered->filter($func);
            }

            // Flag of "USE_BOTH is passed
            $filtered = new OStringFilterWithValueAndKey($this->string);
            return $filtered->filter($func);
        } else {
            throw new \InvalidArgumentException("Invalid flag name");
        }
    }
}
