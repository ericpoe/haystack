<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringFilterWithKey
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

    /**
     * @param callable $func
     * @return HString
     */
    public function filter(callable $func)
    {
        $filtered = new HString();
        foreach ($this->hString as $letter) {
            if (true === (bool) $func($this->hString->key())) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
