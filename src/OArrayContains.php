<?php
namespace OPHP;

use OPHP\Helpers\Helper;

class OArrayContains
{
    /** @var bool */
    private $answer;

    /** @var Helper */
    private $helper;

    /**
     * @param OArray $arr
     * @param        $value
     */
    public function __construct(OArray $arr, $value)
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
