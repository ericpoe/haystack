<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HaystackStringLocate
{
    /** @var HString */
    private $string;

    public function __construct(HString $string)
    {
        $this->string = $string;
    }

    /**
     * @param $value
     * @return int
     * @throws ElementNotFoundException
     * @throws \InvalidArgumentException
     */
    public function locate($value)
    {
        if (is_scalar($value) || $value instanceof HString) {
            if ($this->string->contains($value)) {
                return strpos($this->string, (string) $value);
            }

            throw new ElementNotFoundException($value);
        }

        throw new \InvalidArgumentException(sprintf("%s is neither a scalar value nor an HString", Helper::getType($value)));
    }

}
