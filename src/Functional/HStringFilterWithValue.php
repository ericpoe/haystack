<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringFilterWithValue
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
    public function filter(callable $func)
    {
        $filtered = new HString();

        foreach ($this->hString as $letter) {
            if ($func($letter)) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
