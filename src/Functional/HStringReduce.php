<?php
namespace Haystack\Functional;

use Haystack\HArray;
use Haystack\HString;

class HStringReduce
{
    /** @var HString */
    private $string;

    /**
     * @param HString $string
     */
    public function __construct(HString $string)
    {
        $this->string = $string;
    }

    /**
     * @param callable $func
     * @param $initial
     * @return bool|float|int|HString|HArray
     */
    public function reduce(callable $func, $initial)
    {
        $reduced = $initial;

        foreach ($this->string as $letter) {
            $reduced = $func($reduced, $letter);
        }

        if ($reduced instanceof \ArrayObject || is_array($reduced)) {
            return new HArray($reduced);
        }

        if (is_string($reduced)) {
            return new HString($reduced);
        }

        return $reduced;
    }
}
