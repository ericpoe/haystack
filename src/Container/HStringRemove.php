<?php
namespace Haystack\Container;

use Haystack\HString;

class HStringRemove
{
    /** @var HString */
    private $hString;

    public function __construct(HString $hString)
    {
        $this->hString = $hString;
    }

    /**
     * @param $value
     * @return HString
     */
    public function remove($value)
    {
        $key = $this->hString->locate($value);

        return new HString($this->getPrefix($key) . $this->getSuffix($key));
    }

    private function getPrefix($length)
    {
        return mb_substr($this->hString, 0, $length, $this->hString->getEncoding());
    }

    private function getSuffix($start)
    {
        return mb_substr($this->hString, $start + 1, $this->hString->count() - $start, $this->hString->getEncoding());
    }
}
