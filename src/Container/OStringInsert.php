<?php
namespace OPHP\Container;

use OPHP\Helpers\Helper;
use OPHP\OString;

class OStringInsert
{
    private $helper;
    private $string;

    public function __construct(OString $string)
    {
        $this->helper = new Helper();
        $this->string = $string;
    }

    public function insert($value, $key = null)
    {
        if (is_scalar($value) || $value instanceof OString) {
            if (is_null($key)) {
                $key = strlen($this->string);
            } elseif (is_numeric($key)) {
                $key = (int) $key;
            } else {
                throw new \InvalidArgumentException("Invalid array key");
            }

            return substr_replace($this->string, $value, $key, 0);
        }

        throw new \InvalidArgumentException("Cannot insert {$this->helper->getType($value)} into an OString");
    }

}
