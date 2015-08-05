<?php
namespace OPHP\Filter;

use OPHP\OArray;

class OArrayFilterWithValue extends OArray
{
    /** @var OArray */
    protected $arr;

    /** @var callable */
    private $func;

    public function __construct(OArray &$arr, callable $func)
    {
        parent::__construct($arr);

        $this->func = $func;

        $this->arr = $this->run();
    }

    private function run()
    {
        return array_filter($this->arr->toArray(), $this->func);
    }
}
