<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HStringContains
{
    /** @var string */
    private $str;

    /** @var string */
    private $value;

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
        if ($value instanceof HString) {
            $value = $value->toString();
        }

        if (is_scalar($value)) {
            $this->value = (string) $value;
        } else {
            throw new \InvalidArgumentException(sprintf("%s is neither a scalar value nor an HString", Helper::getType($value)));
        }

        return $this->containsValue();
    }

    /**
     * @return bool
     */
    private function containsValue()
    {
        $pos = strpos($this->str, $this->value);

        return (false !== $pos) ?: false;
    }
}