<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringFilterWithKey
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

    /**
     * @param callable $func
     * @return HString
     */
    public function filter(callable $func)
    {
        $filtered = new HString();
        foreach ($this->string as $letter) {
            if (true === (bool) $func($this->string->key())) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
