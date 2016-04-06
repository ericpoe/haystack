<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HaystackStringContains
{
    /** @var string */
    private $str;

    /** @var HString|string */
    private $value;

    /** @var  boolean */
    private $flag;


    public function __construct(HString $hString)
    {
        $this->str = $hString->toString();
    }

    /**
     * @param HString|string $value
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
            throw new \InvalidArgumentException(sprintf("%s is neither a scalar value nor an HString", Helper::getType($value)));
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
        $pos = strpos($this->str, $newValue);

        return (false !== $pos) ?: false;
    }
}
