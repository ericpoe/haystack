<?php
namespace OPHP;

class StringToArray
{
    /** @var string */
    private $string;

    /** @var OString|string */
    private $delim;

    /** @var array */
    private $arr;

    /**
     * @param $string
     * @param string $delim
     */
    public function __construct($string, $delim = " ")
    {
        $this->string = $string;

        if (empty($delim) || is_string($delim) || $delim instanceof OString) {
            $this->delim = $delim;
        } else {
            throw new \InvalidArgumentException("delimiter must be a string");
        }
    }

    /**
     * @param int|null $limit
     * @return array
     */
    public function stringToArray($limit)
    {
        if (empty($this->delim)) {
            $this->arr = $this->noDelimExplode();
            return $this->arr;
        }

        if (is_null($limit)) {
            $this->arr = $this->noLimitExplode();
            return $this->arr;
        }

        if (is_integer($limit)) {
            $this->arr = $this->explode($limit);
            return $this->arr;
        } else {
            throw new \InvalidArgumentException("limit must be an integer");
        }
    }

    /**
     * @return array
     */
    private function noDelimExplode()
    {
        return explode(" ", $this->string);
    }

    /**
     * @return array
     */
    private function noLimitExplode()
    {
        return explode($this->delim, $this->string);
    }

    /**
     * @param int $limit
     * @return array
     */
    private function explode($limit)
    {
        return explode($this->delim, $this->string, $limit);
    }
}
