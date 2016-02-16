<?php
namespace OPHP\Converter;

use OPHP\OString;

class ArrayToString
{
    /** @var  array */
    private $arr;

    /** @var OString|string */
    private $glue;

    /**
     * @param array  $arr
     * @param string $glue
     */
    public function __construct(array $arr, $glue = "")
    {
        $this->arr = $arr;

        $this->glue = empty($glue) ? "" : $glue;
    }

    /**
     * @return string
     */
    public function toString()
    {
        if (is_string($this->glue) || $this->glue instanceof OString) {
            return $this->arrayImplode();
        }

        throw new \InvalidArgumentException("glue must be a string");
    }

    /**
     * @return string
     */
    private function arrayImplode()
    {
        return implode($this->glue, $this->arr);
    }
}