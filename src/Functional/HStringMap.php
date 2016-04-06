<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringMap
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

    public function map(callable $func)
    {
        $newString = new HString($this->hString);

        $size = $this->hString->count();
        for ($i = 0; $i < $size; $i++) {
            $newString[$i] = $func($this->hString[$i]);
        }

        return $newString;
    }

}
