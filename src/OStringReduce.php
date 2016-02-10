<?php
namespace OPHP;

class OStringReduce
{
    /** @var OString */
    private $string;

    /**
     * @param OString $string
     */
    public function __construct(OString $string)
    {
        $this->string = $string;
    }

    /**
     * @param callable $func
     * @param $initial
     * @return bool|float|int|OString|OArray
     */
    public function reduce(callable $func, $initial)
    {
        $reduced = $initial;

        foreach ($this->string as $letter) {
            $reduced = $func($reduced, $letter);
        }

        if ($reduced instanceof \ArrayObject || is_array($reduced)) {
            return new OArray($reduced);
        }

        if (is_string($reduced)) {
            return new OString($reduced);
        }

        return $reduced;
    }
}
