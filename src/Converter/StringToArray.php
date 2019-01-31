<?php
declare(strict_types=1);

namespace Haystack\Converter;

class StringToArray
{
    /** @var string */
    private $str;

    /** @var string */
    private $delim;

    /** @var array */
    private $arr;

    public function __construct(string $str, ?string $delim = '')
    {
        $this->str = $str;

        if (is_string($delim)) {
            $this->delim = $delim;
        } else {
            throw new \InvalidArgumentException('delimiter must be a string');
        }
    }

    /**
     * @throws HaystackConverterException
     * @throws \InvalidArgumentException
     */
    public function stringToArray(?int $limit = 0): array
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

    private function noDelimExplode(): array
    {
        $arr = preg_split('//u', $this->str, -1, PREG_SPLIT_NO_EMPTY);

        if (!$arr) {
            throw new HaystackConverterException('Cannot convert this HString to an array');
        }

        return $arr;
    }

    private function noLimitExplode(): array
    {
        $arr = explode($this->delim, $this->str);

        if (false === $arr) {
            throw new HaystackConverterException('Cannot convert this HString to an array');
        }

        return $arr;
    }

    private function explode(int $limit): array
    {
        $arr = explode($this->delim, $this->str, $limit);

        if (false === $arr) {
            throw new HaystackConverterException('Cannot convert this HString to an array');
        }

        return $arr;
    }
}
