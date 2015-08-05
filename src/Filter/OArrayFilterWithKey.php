<?php
namespace OPHP\Filter;

use OPHP\OArray;

class OArrayFilterWithKey extends OArray
{
    /** @var  OArray*/
    protected $arr;

    /** @var  array*/
    private $filtered;

    public function __construct(OArray &$arr, callable $func)
    {
        parent::__construct($arr);

        if (version_compare(phpversion(), 5.6) >= 0) {
            $this->internalFilterWithKey($func);
        } else {
            $this->legacyFilterWithKey($func);
        }

        $this->arr = $this->filtered;
    }

    private function internalFilterWithKey($func)
    {
        $this->filtered = array_filter($this->arr->toArray(), $func, ARRAY_FILTER_USE_KEY);
    }

    private function legacyFilterWithKey($func)
    {
        $filtered = new OArray();
        foreach ($this as $key => $value) {
            if (true === (bool) $func($key)) {
                $filtered = $filtered->insert($value, $key);
            }
        }
        $this->filtered = $filtered->toArray();
    }
}
