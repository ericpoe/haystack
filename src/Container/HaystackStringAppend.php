<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HaystackStringAppend
{
    private $helper;
    private $string;

    public function __construct(HString $string)
    {
        $this->helper = new Helper();
        $this->string = $string;
    }

    public function append($value)
    {
        if (is_scalar($value) || $value instanceof HString) {
            return $this->string . $value;
        }
        throw new \InvalidArgumentException("Cannot concatenate an HString with a {$this->helper->getType($value)}");
    }
}
