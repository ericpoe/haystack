<?php
namespace OPHP;

use OPHP\Helpers\Helper;

class OArrayContains
{
    /**
     * @var OArray
     */
    private $arr;

    /** @var Helper */
    private $helper;

    public function __construct(OArray $arr)
    {
        $this->arr = $arr;
        $this->helper = new Helper();
    }

    /**
     * @param $value
     * @return bool
     */
    public function contains($value)
    {
        if ($this->helper->canBeInArray($value)) {
            $arr = $this->arr->toArray();
            $answer = in_array($value, $arr);
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} cannot be contained within an OArray");
        }
        return $answer;
    }
}
