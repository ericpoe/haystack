<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringMap
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

    public function map(callable $func)
    {
        $newString = new HString($this->string);

        $size = $this->string->count();
        for ($i = 0; $i < $size; $i++) {
            $newString[$i] = $func($this->string[$i]);
        }

        return $newString;
    }

}
