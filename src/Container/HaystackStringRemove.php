<?php
namespace Haystack\Container;

use Haystack\HString;

class HaystackStringRemove
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
        $startString = $this->hString->slice(0, $key);
        $endString = $this->hString->slice($key + 1);

        return $startString->insert($endString);
    }

}
