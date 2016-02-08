<?php
namespace OPHP;

class OStringRemove
{
    private $string;

    public function __construct(OString $string)
    {
        $this->string = $string;
    }

    /**
     * @param $value
     * @return OString
     */
    public function remove($value)
    {
        $key = $this->string->locate($value);
        $startString = $this->string->slice(0, $key);
        $endString = $this->string->slice($key + 1);

        return $startString->insert($endString);
    }

}
