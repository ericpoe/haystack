<?php
namespace OPHP;

use OPHP\Helpers\Helper;

class OStringContains
{
    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var \OPHP\OString
     */
    private $string;

    private $flag;


    public function __construct(OString $string, $value)
    {
        $this->helper = new Helper();
        $this->string = $string;

        if (is_scalar($value)) {
            $this->containsScalar($value);
        } elseif ($value instanceof OString) {
            $this->containsOString($value);
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} is neither a scalar value nor an OString");
        }
    }

    public function getAnswer()
    {
        return $this->flag;
    }

    private function containsScalar($value)
    {
        $newValue = (string)$value;
        $this->flag = $this->containsValue($newValue);
    }

    private function containsOString(OString $value)
    {
        $newValue = $value->toString();
        $this->flag = $this->containsValue($newValue);
    }

    private function containsValue($value)
    {
        $pos = strstr($this->string, $value);

        return (false != $pos) ?: false;
    }
}
