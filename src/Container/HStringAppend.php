<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HStringAppend
{
    /** @var HString */
    private $hString;

    public function __construct(HString $hString)
    {
        $this->hString = $hString;
    }

    public function append($value)
    {
        if (is_scalar($value) || $value instanceof HString) {
            return $this->hString . $value;
        }
        throw new \InvalidArgumentException(sprintf('Cannot concatenate an HString with a %s', Helper::getType($value)));
    }
}
