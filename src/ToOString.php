<?php

namespace OPHP;

class ToOString
{
    /** @var  array */
    private $arr;

    /** @var OString|string */
    private $glue;
    private $string;

    /**
     * @param array  $arr
     * @param string $glue
     */
    public function __construct(array $arr, $glue = "")
    {
        $this->arr = $arr;

        if (empty($glue)) {
            $this->glue = "";
        } else {
            $this->glue = $glue;
        }

        if (is_string($this->glue) || $this->glue instanceof OString) {
            $this->string = $this->oArrayImplode();
            return $this->string;
        }

        throw new \InvalidArgumentException("glue must be a string");
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->string;
    }

    /**
     * @return string
     */
    private function oArrayImplode()
    {
        return implode($this->glue, $this->arr);
    }
}
