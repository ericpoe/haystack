<?php
namespace OPHP\Filter;

use OPHP\Helpers\ArrayHelper;
use OPHP\OArray;

class OArrayFilterWithDefaults extends OArray
{
    /** @var  OArray*/
    protected $arr;

    public function __construct(OArray &$arr)
    {
        parent::__construct($arr);

        $this->arr = $this->run();
    }

    private function run()
    {
        $filtered = array_filter($this->arr->toArray());

        if (ArrayHelper::isAssociativeArray($filtered)) {
            return $filtered;
        }

        return array_values($filtered);
    }
}
