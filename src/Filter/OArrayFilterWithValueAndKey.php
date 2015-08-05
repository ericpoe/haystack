<?php
namespace OPHP\Filter;

use OPHP\OArray;

class OArrayFilterWithValueAndKey extends OArray
{
    /** @var  OArray*/
    protected $arr;

    /** @var  array*/
    private $filtered;

    public function __construct(OArray &$arr, callable $func)
    {
        parent::__construct($arr);

        if (version_compare(phpversion(), 5.6) >= 0) {
            $this->internalFilterWithBoth($func);
        } else {
            $this->legacyFilterWithBoth($func);
        }

        $this->arr = $this->filtered;
    }

    private function internalFilterWithBoth($func)
    {
        $this->filtered = array_filter($this->arr->toArray(), $func, ARRAY_FILTER_USE_BOTH);
    }

    private function legacyFilterWithBoth($func)
    {
        $filtered = new OArray();
        foreach ($this as $key => $value) {
            if (true === (bool) $func($value, $key)) {
                $filtered = $filtered->insert($value, $key);
            }
        }
        $this->filtered = $filtered->toArray();
    }
}
