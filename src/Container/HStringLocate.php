<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HStringLocate
{
    /** @var HString */
    private $hString;

    public function __construct(HString $str)
    {
        $this->hString = $str;
    }

    /**
     * @param $value
     * @return int
     * @throws ElementNotFoundException
     */
    public function locate($value)
    {
        if ($this->hString->contains($value)) {
            return strpos($this->hString, (string) $value);
        }

        throw new ElementNotFoundException($value);
    }

}
