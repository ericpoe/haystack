<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringFilterWithDefaults
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
     * @return HString
     */
    public function filter()
    {
        $filtered = new HString();
        foreach ($this->hString as $letter) {
            if ((bool) $letter) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
