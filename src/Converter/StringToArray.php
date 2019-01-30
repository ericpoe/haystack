<?php
namespace Haystack\Converter;

use Haystack\HString;

class StringToArray
{
    /** @var string */
    private $str;

    /** @var string */
    private $delim;

    /** @var array */
    private $arr;

    /**
     * @param string | HString $str
     * @param string | HString $delim
     */
    public function __construct($str, $delim = '')
    {
        $this->str = (string) $str;

        if (empty($delim) || is_string($delim) || $delim instanceof HString) {
            $this->delim = (string) $delim;
        } else {
            throw new \InvalidArgumentException('delimiter must be a string');
        }
    }

    /**
     * @param null|int $limit
     * @return array
     */
    /**
     * @param null $limit
     * @return array
     * @throws \InvalidArgumentException
     */
    public function stringToArray($limit = null)
    {
        if ($this->delim === null || '' === $this->delim) {
            $this->arr = $this->noDelimExplode();
            return $this->arr;
        }

        if ($limit === null) {
            $this->arr = $this->noLimitExplode();
            return $this->arr;
        }

        if (is_int($limit)) {
            $this->arr = $this->explode($limit);
            return $this->arr;
        }

        throw new \InvalidArgumentException('limit must be an integer');
    }

    /**
     * @return array
     */
    private function noDelimExplode()
    {
        return preg_split('//u', $this->str, -1, PREG_SPLIT_NO_EMPTY);
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
