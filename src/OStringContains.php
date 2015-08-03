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
     * @var OString
     */
    private $string;

    /**
     * @var OString|string
     */
    private $value;

    /** @var  boolean */
    private $flag;


    public function __construct(OString &$string, &$value)
    {
        $this->helper = new Helper();
        $this->string = $string->toString();
        $this->value = $value;

        if (is_scalar($this->value)) {
            $this->containsScalar($this->value);
        } elseif ($this->value instanceof OString) {
            $this->containsOString($this->value);
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} is neither a scalar value nor an OString");
        }
    }

    /**
     * @return boolean
     */
    public function isContained()
    {
        return $this->flag;
    }

    private function containsScalar()
    {
        $newValue = (string)$this->value;
        $this->flag = $this->containsValue($newValue);
    }

    private function containsOString()
    {
        $newValue = $this->value->toString();
        $this->flag = $this->containsValue($newValue);
    }

    /**
     * @param string|OString $value
     * @return bool
     */
    private function containsValue(&$newValue)
    {
        $pos = strstr($this->string, $newValue);

        return (false !== $pos) ?: false;
    }
}
