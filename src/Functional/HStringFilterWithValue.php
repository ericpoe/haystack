<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringFilterWithValue
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
    public function filter(callable $func)
    {
        $filtered = new HString();

        foreach ($this->string as $letter) {
            if ($func($letter)) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
