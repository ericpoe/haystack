<?php
namespace Haystack\Converter;

use Haystack\HString;

class StringToArray
{
    /** @var string */
    private $str;

    /** @var HString|string */
    private $delim;

    /** @var array */
    private $arr;

    /**
     * @param string $str
     * @param string $delim
     */
    public function __construct($str, $delim = "")
    {
        $this->str = $str;

        if (empty($delim) || is_string($delim) || $delim instanceof HString) {
            $this->delim = $delim;
        } else {
            throw new \InvalidArgumentException("delimiter must be a string");
        }
    }

    /**
     * @param int|null $limit
     * @return array
     */
    public function stringToArray($limit = null)
    {
        if (is_null($this->delim)) {
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
        return str_split($this->str);
    }

    /**
     * @return array
     */
    private function noLimitExplode()
    {
        return explode($this->delim, $this->str);
    }

    /**
     * @param int $limit
     * @return array
     */
    private function explode($limit)
    {
        return explode($this->delim, $this->str, $limit);
    }
}
