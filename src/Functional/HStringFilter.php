<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringFilter
{
    /** @var HString */
    protected $hSTring;

    public function __construct(HString $hString)
    {
        $this->hSTring = $hString;
    }
    public function filter(callable $func = null, $flag = null)
    {
        // Default
        if (is_null($func)) {
            $filtered = new HStringFilterWithDefaults($this->hSTring);
            return $filtered->filter();
        }

        // No flags are passed
        if (is_null($flag)) {
            $filtered = new HStringFilterWithValue($this->hSTring);
            return $filtered->filter($func);
        }

        if ("key" === $flag || "both" === $flag) {
            // Flag of "USE_KEY" is passed
            if ("key" === $flag) {
                $filtered = new HStringFilterWithKey($this->hSTring);
                return $filtered->filter($func);
            }

            // Flag of "USE_BOTH is passed
            $filtered = new HStringFilterWithValueAndKey($this->hSTring);
            return $filtered->filter($func);
        } else {
            throw new \InvalidArgumentException("Invalid flag name");
        }
    }
}
