<?php
namespace OPHP\Filter;

use OPHP\OString;

class OStringFilterWithValueAndKey
{
    private $string;

    public function __construct(OString $string)
    {
        $this->string = $string;
    }

    public function filter(callable &$func)
    {
        $filtered = new OString();

        foreach ($this->string as $letter) {
            if (true === (bool) $func($letter, $this->string->key())) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
