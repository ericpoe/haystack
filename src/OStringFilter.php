<?php
namespace OPHP;

use OPHP\Filter\OStringFilterWithDefaults;
use OPHP\Filter\OStringFilterWithKey;
use OPHP\Filter\OStringFilterWithValue;
use OPHP\Filter\OStringFilterWithValueAndKey;

class OStringFilter extends OString
{
    /** @var OString */
    protected $string;

    /** @var OString */
    protected $filtered;

    public function __construct(OString &$string, callable $func = null, $flag = null)
    {
        $this->string = $string;
        $this->filtered = new OString();

        // Default
        if (is_null($func)) {
            $this->filterWithDefaults();
            return $this->filtered;
        }

        // No flags are passed
        if (is_null($flag)) {
            $this->filterWithValue($func);
            return $this->filtered;
        }

        if ("key" === $flag || "both" === $flag) {
            // Flag of "USE_KEY" is passed
            if ("key" === $flag) {
                $this->filterWithKey($func);
                return $this->filtered;
            }

            // Flag of "USE_BOTH is passed
            $this->filterWithValueAndKey($func);
            return $this->filtered;
        } else {
            throw new \InvalidArgumentException("Invalid flag name");
        }
    }

    public function toString()
    {
        return $this->filtered->toString();
    }

    /**
     * @return OString
     */
    private function filterWithDefaults()
    {
        return new OStringFilterWithDefaults($this->string, $this->filtered);
    }

    /**
     * @param callable $func
     * @return OString
     */
    private function filterWithValue(callable $func)
    {
        return new OStringFilterWithValue($this->string, $this->filtered, $func);
    }

    /**
     * @param callable $func
     * @return OString
     */
    private function filterWithKey(callable $func)
    {
        return new OStringFilterWithKey($this->string, $this->filtered, $func);
    }

    /**
     * @param callable $func
     * @return OString
     */
    private function filterWithValueAndKey(callable $func)
    {
        return new OStringFilterWithValueAndKey($this->string, $this->filtered, $func);
    }
}
