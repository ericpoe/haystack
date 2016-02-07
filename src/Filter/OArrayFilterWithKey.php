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

        $this->filterWithKey($func);

        $this->arr = $this->filtered;
    }

    private function filterWithKey($func)
    {
        $this->filtered = array_filter($this->arr->toArray(), $func, ARRAY_FILTER_USE_KEY);
    }
}
