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
                $key = $this->hString->count();
            } elseif (is_numeric($key)) {
                $key = (int) $key;
            } else {
                throw new \InvalidArgumentException("Invalid array key");
            }

            return $this->getPrefix($key). $value . $this->getSuffix($key);
        }

        throw new \InvalidArgumentException(sprintf("Cannot insert %s into an HString", Helper::getType($value)));
    }

    private function getPrefix($key)
    {
        $length = $key >= 0 ? $key: $this->hString->count() - 1;

        return mb_substr($this->hString, 0, $length, $this->hString->getEncoding());
    }

    private function getSuffix($key)
    {
        $start = $key >= 0 ? $key : $this->hString->count() + $key;
        $length = $key >= 0 ? $this->hString->count() : $this->hString->count() + $key;

        return mb_substr($this->hString, $start, $length, $this->hString->getEncoding());
    }

}
