<?php
namespace OPHP;

class OArrayToString
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
            return $this->oArrayImplode();
        }

        throw new \InvalidArgumentException("glue must be a string");
    }

    /**
     * @return string
     */
    private function oArrayImplode()
    {
        return implode($this->glue, $this->arr);
    }
}
