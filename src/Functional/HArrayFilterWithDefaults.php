<?php
namespace Haystack\Functional;

use Haystack\Helpers\Helper;

class HArrayFilterWithDefaults
{
    /** @var array*/
    protected $arr;

    /**
     * @param array $arr
     */
    public function __construct(array $arr)
    {
        $this->arr = $arr;
    }

    /**
     * @return array
     */
    public function filter()
    {
        $filtered = array_filter($this->arr);

        if (Helper::isAssociativeArray($filtered)) {
            return $filtered;
        }

        return array_values($filtered);
    }
}
