<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HaystackStringContains
{
    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var HString
     */
    private $string;

    /**
     * @var HString|string
     */
    private $value;

    /** @var  boolean */
    private $flag;


    public function __construct(HString $string)
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
        } elseif ($value instanceof HString) {
            $this->containsHString();
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} is neither a scalar value nor an HString");
        }

        return $this->flag;
    }

    private function containsScalar()
    {
        $newValue = (string)$this->value;
        $this->flag = $this->containsValue($newValue);
    }

    private function containsHString()
    {
        $newValue = $this->value->toString();
        $this->flag = $this->containsValue($newValue);
    }

    /**
     * @param HString|string $newValue
     * @return bool
     */
    private function containsValue($newValue)
    {
        $pos = strstr($this->string, $newValue);

        return (false !== $pos) ?: false;
    }
}
