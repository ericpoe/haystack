<?php
namespace OPHP;

class ToOArray
{
    /** @var string */
    private $string;

    /** @var OString|string */
    private $delim;

    /** @var array */
    private $arr;

    /**
     * @param           $string
     * @param string    $delim
     * @param int|null  $limit
     */
    public function __construct($string, $delim = " ", $limit = null)
    {
        $this->string = $string;

        if (empty($delim)) {
            return $this->noDelimExplode();
        }

        if (is_string($delim) || $delim instanceof OString) {
            $this->delim = $delim;
        } else {
            throw new \InvalidArgumentException("delimiter must be a string");
        }

        if (is_null($limit)) {
            return $this->noLimitExplode();
        }

        if (is_integer($limit)) {
            return $this->explode($limit);
        } else {
            throw new \InvalidArgumentException("limit must be an integer");
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->arr;
    }

    private function noDelimExplode()
    {
        $this->arr = explode(" ", $this->string);
    }

    private function noLimitExplode()
    {
        $this->arr = explode($this->delim, $this->string);
    }

    /**
     * @param int   $limit
     */
    private function explode($limit)
    {
        $this->arr = explode($this->delim, $this->string, $limit);
    }
}
