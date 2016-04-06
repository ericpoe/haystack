<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringFilterWithValueAndKey
{
    /** @var HString */
    private $hString;

    public function __construct(HString $hString)
    {
        $this->hString = $hString;
    }

    public function filter(callable &$func)
    {
        $filtered = new HString();

        foreach ($this->hString as $letter) {
            if (true === (bool) $func($letter, $this->hString->key())) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
