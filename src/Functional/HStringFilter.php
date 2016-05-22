<?php
namespace Haystack\Functional;

use Haystack\HArray;
use Haystack\HString;

class HStringFilter
{
    /** @var HString */
    protected $hString;

    public function __construct(HString $hString)
    {
        $this->hString = $hString;
    }
    public function filter(callable $func = null, $flag = null)
    {
        // Default
        if (is_null($func)) {
            $filtered = new HaystackFilterWithDefaults($this->hString->toArray());
            return (new HArray($filtered->filter()))->toHString();
        }

        // No flags are passed
        if (is_null($flag)) {
            $filtered = new HaystackFilterWithValue($this->hString->toArray());
            return (new HArray($filtered->filter($func)))->toHString();
        }

        if ("key" === $flag || "both" === $flag) {
            // Flag of "USE_KEY" is passed
            if ("key" === $flag) {
                $filtered = new HaystackFilterWithKey($this->hString->toArray());
                return (new HArray($filtered->filter($func)))->toHString();
            }

            // Flag of "USE_BOTH is passed
            $filtered = new HStringFilterWithValueAndKey($this->hString);
            return $filtered->filter($func);
        } else {
            throw new \InvalidArgumentException("Invalid flag name");
        }
    }
}
