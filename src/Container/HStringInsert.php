<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HStringInsert
{
    /** @var HString */
    private $hString;

    public function __construct(HString $hString)
    {
        $this->hString = $hString;
    }

    public function insert($value, $key = null)
    {
        if (is_scalar($value) || $value instanceof HString) {
            if (is_null($key)) {
                $key = strlen($this->hString);
            } elseif (is_numeric($key)) {
                $key = (int) $key;
            } else {
                throw new \InvalidArgumentException("Invalid array key");
            }

            return substr_replace($this->hString, $value, $key, 0);
        }

        throw new \InvalidArgumentException(sprintf("Cannot insert %s into an HString", Helper::getType($value)));
    }

}
