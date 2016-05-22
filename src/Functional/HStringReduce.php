<?php
namespace Haystack\Functional;

use Haystack\HArray;
use Haystack\HString;

class HStringReduce
{
    /** @var HString */
    private $hString;

    /**
     * @param HString $hString
     */
    public function __construct(HString $hString)
    {
        $this->hString = $hString;
    }

    /**
     * @param callable $func
     * @param $initial
     * @return bool|float|int|HString|HArray
     */
    public function reduce(callable $func, $initial)
    {
        $reduced = array_reduce($this->hString->toArray(), $func, $initial);

        if ($reduced instanceof \ArrayObject || is_array($reduced)) {
            return new HArray($reduced);
        }

        if (is_string($reduced)) {
            return new HString($reduced);
        }

        return $reduced;
    }
}
