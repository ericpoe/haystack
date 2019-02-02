<?php
declare(strict_types=1);

namespace Haystack\Container;

use Haystack\HString;

class HStringSlice
{
    /** @var HString */
    private $str;

    /**
     * @param HString $hString
     */
    public function __construct(HString $hString)
    {
        $this->str = $hString;
    }

    public function slice(int $start, ?int $length = null): string
    {
        if (!is_numeric($start)) {
            throw new \InvalidArgumentException('Slice parameter 1, $start, must be an integer');
        }

        if ($length !== null && !is_numeric($length)) {
            throw new \InvalidArgumentException('Slice parameter 2, $length, must be null or an integer');
        }

        return mb_substr((string) $this->str, $start, $length, $this->str->getEncoding());
    }
}
