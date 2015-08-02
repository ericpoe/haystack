<?php
namespace OPHP;

use OPHP\Helpers\Helper;

class OArrayContains
{
    private $answer;
    private $helper;

    public function __construct($arr, $value)
    {
        $this->helper = new Helper();

        if ($this->helper->canBeInArray($value)) {
            $this->answer = in_array($value, $arr->toArray());
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} cannot be contained within an OArray");
        }
    }

    /**
     * @return bool
     */
    public function isContained()
    {
        return $this->answer;
    }
}
