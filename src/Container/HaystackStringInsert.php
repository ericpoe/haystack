<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HaystackStringInsert
{
    private $helper;
    private $string;

    public function __construct(HString $string)
    {
        $this->helper = new Helper();
        $this->string = $string;
    }

    public function insert($value, $key = null)
    {
        if (is_scalar($value) || $value instanceof HString) {
            if (is_null($key)) {
                $key = strlen($this->string);
            } elseif (is_numeric($key)) {
                $key = (int) $key;
            } else {
                throw new \InvalidArgumentException("Invalid array key");
            }

            return substr_replace($this->string, $value, $key, 0);
        }

        throw new \InvalidArgumentException("Cannot insert {$this->helper->getType($value)} into an HString");
    }

}
