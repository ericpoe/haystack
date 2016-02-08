<?php
namespace OPHP\Filter;

use OPHP\OString;

class OStringFilterWithDefaults
{
    /**
     * @var OString
     */
    private $string;

    /**
     * @param OString $string
     */
    public function __construct(OString $string)
    {
        $this->string = $string;
    }

    /**
     * @return OString
     */
    public function filter()
    {
        $filtered = new OString();
        foreach ($this->string as $letter) {
            if ((bool) $letter) {
                $filtered = $filtered->insert($letter);
            }
        }

        return $filtered;
    }
}
