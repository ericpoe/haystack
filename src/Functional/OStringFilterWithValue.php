<?php
namespace OPHP\Functional;

use OPHP\OString;

class OStringFilterWithValue
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
    public function filter(callable $func){
        $filtered = new OString();

        foreach ($this->string as $letter) {
            if ($func($letter)) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
