<?php
namespace OPHP;

use OPHP\Helpers\Helper;

class OArrayAppend
{
    /** @var  Helper */
    private $helper;

    /** @var \ArrayObject */
    private $arr;

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->helper = new Helper();
        $this->arr = new \ArrayObject($array);
    }

    /**
     * @param OArray|array|numeric|string $value
     * @return \ArrayObject
     */
    public function append($value)
    {
        $value = $value instanceof OArray ? $value->toArray() : $value;

        if ($this->helper->canBeInArray($value)) {
            $this->arr->append($value);

            return $this->arr;
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} cannot be appended to an OArray");
        }
    }

}
