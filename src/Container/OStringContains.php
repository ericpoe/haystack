<?php
namespace OPHP\Container;

use OPHP\Helpers\Helper;
use OPHP\OString;

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


    public function __construct(OString $string)
    {
        $this->helper = new Helper();
        $this->string = $string->toString();
    }

    /**
     * @param string $value
     * @return bool
     */
    public function contains($value)
    {
        $this->value = $value;

        if (is_scalar($value)) {
            $this->containsScalar();
        } elseif ($value instanceof OString) {
            $this->containsOString();
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} is neither a scalar value nor an OString");
        }

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
     * @param OString|string $value
     * @return bool
     */
    private function containsValue($newValue)
    {
        $pos = strstr($this->string, $newValue);

        return (false !== $pos) ?: false;
    }
}
