<?php
namespace OPHP\Container;

use OPHP\Helpers\Helper;
use OPHP\OString;

class OStringAppend
{
    private $helper;
    private $string;

    public function __construct(OString $string)
    {
        $this->helper = new Helper();
        $this->string = $string;
    }

    public function append($value)
    {
        if (is_scalar($value) || $value instanceof OString) {
            return $this->string . $value;
        }
        throw new \InvalidArgumentException("Cannot concatenate an OString with a {$this->helper->getType($value)}");
    }
}
