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

        $this->filterWithBoth($func);

        $this->arr = $this->filtered;
    }

    private function filterWithBoth($func)
    {
        $this->filtered = array_filter($this->arr->toArray(), $func, ARRAY_FILTER_USE_BOTH);
    }
}
