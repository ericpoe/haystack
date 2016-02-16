<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringFilterWithValueAndKey
{
    private $string;

    public function __construct(HString $string)
    {
        $this->string = $string;
    }

    public function filter(callable &$func)
    {
        $filtered = new HString();

        foreach ($this->string as $letter) {
            if (true === (bool) $func($letter, $this->string->key())) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
