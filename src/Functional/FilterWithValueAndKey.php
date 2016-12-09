<?php
namespace Haystack\Functional;

class FilterWithValueAndKey
{
    /** @var array */
    protected $arr;

    /**
     * @param array $arr
     */
    public function __construct(array $arr)
    {
        $this->arr = $arr;
    }

    /**
     * @param callable $func
     * @return array
     */
    public function filter(callable $func)
    {
        return array_filter($this->arr, $func, ARRAY_FILTER_USE_BOTH);
    }
}
