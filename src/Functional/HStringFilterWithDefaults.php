<?php
namespace Haystack\Functional;

use Haystack\HString;

class HStringFilterWithDefaults
{
    /**
     * @var HString
     */
    private $string;

    /**
     * @param HString $string
     */
    public function __construct(HString $string)
    {
        $this->string = $string;
    }

    /**
     * @return HString
     */
    public function filter()
    {
        $filtered = new HString();
        foreach ($this->string as $letter) {
            if ((bool) $letter) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
