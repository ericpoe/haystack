<?php
namespace Haystack\Container;

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
     * @param HString|string $value
     * @return int
     * @throws ElementNotFoundException
     */
    public function locate($value)
    {
        if ($this->hString->contains($value)) {
            return mb_strpos($this->hString, (string) $value, null, $this->hString->getEncoding());
        }

        throw new ElementNotFoundException((string) $value);
    }

}
