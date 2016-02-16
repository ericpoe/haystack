<?php
namespace OPHP\Functional;

use OPHP\Helpers\ArrayHelper;

class OArrayFilterWithDefaults
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

        if (ArrayHelper::isAssociativeArray($filtered)) {
            return $filtered;
        }

        return array_values($filtered);
    }
}
