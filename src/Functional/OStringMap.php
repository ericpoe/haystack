<?php
namespace OPHP\Functional;

use OPHP\OString;

class OStringMap
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

    public function map(callable $func)
    {
        $newString = new OString($this->string);

        $size = $this->string->count();
        for ($i = 0; $i < $size; $i++) {
            $newString[$i] = $func($this->string[$i]);
        }

        return $newString;
    }

}
