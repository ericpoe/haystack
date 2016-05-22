<?php
namespace Haystack\Functional;

class HaystackFilterWithKey
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
        if (!defined('ARRAY_FILTER_USE_KEY')) {
            $return = [];
            foreach ($this->arr as $k => $v) {
                if (call_user_func($func, $k)) {
                    $return[$k] = $v;
                }
            }
            return $return;
        }

        return array_filter($this->arr, $func, ARRAY_FILTER_USE_KEY);
    }
}
