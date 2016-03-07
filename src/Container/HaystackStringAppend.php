<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HaystackStringAppend
{
    private $string;

    public function __construct(HString $string)
    {
        $this->string = $string;
    }

    public function append($value)
    {
        if (is_scalar($value) || $value instanceof HString) {
            return $this->string . $value;
        }
        throw new \InvalidArgumentException(sprintf("Cannot concatenate an HString with a %s", Helper::getType($value)));
    }
}
