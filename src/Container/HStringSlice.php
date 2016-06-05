<?php
namespace Haystack\Container;

use Haystack\HString;

class HStringSlice
{
    /** @var string */
    private $str;

    /**
     * @param HString $hString
     */
    public function __construct(HString $hString)
    {
        $this->str = $hString->toString();
    }

    /**
     * @param int $start
     * @param int|null $length
     * @return string
     */
    public function slice($start, $length = null)
    {
        if (is_null($start) || !is_numeric($start)) {
            throw new \InvalidArgumentException("Slice parameter 1, \$start, must be an integer");
        }

        if (!is_null($length) && !is_numeric($length)) {
            throw new \InvalidArgumentException("Slice parameter 2, \$length, must be null or an integer");
        }

        if (is_null($length)) {
            return substr($this->str, $start);
        }

        return substr($this->str, $start, $length);
    }
}
