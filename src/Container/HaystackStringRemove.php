<?php
namespace Haystack\Container;

use Haystack\HString;

class HaystackStringRemove
{
    private $string;

    public function __construct(HString $string)
    {
        $this->string = $string;
    }

    /**
     * @param $value
     * @return HString
     */
    public function remove($value)
    {
        $key = $this->string->locate($value);
        $startString = $this->string->slice(0, $key);
        $endString = $this->string->slice($key + 1);

        return $startString->insert($endString);
    }

}
