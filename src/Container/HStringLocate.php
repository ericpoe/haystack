<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HStringLocate
{
    /** @var HString */
    private $hString;

    public function __construct(HString $str)
    {
        $this->hString = $str;
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
            if ($this->hString->contains($value)) {
                return strpos($this->hString, (string) $value);
            }

            throw new ElementNotFoundException($value);
        }

        throw new \InvalidArgumentException(sprintf("%s is neither a scalar value nor an HString", Helper::getType($value)));
    }

}
