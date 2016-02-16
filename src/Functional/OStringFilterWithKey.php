<?php
namespace OPHP\Functional;

use OPHP\OString;

class OStringFilterWithKey
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
     * @return OString
     */
    public function filter(callable $func)
    {
        $filtered = new OString();
        foreach ($this->string as $letter) {
            if (true === (bool) $func($this->string->key())) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
