<?php
declare(strict_types=1);

namespace Haystack\Container;

use Haystack\HArray;

class HArraySlice
{
    /** @var array */
    private $arr;

    /**
     * @param HArray $array
     */
    public function __construct(HArray $array)
    {
        $this->arr = $array->toArray();
    }

    public function slice(int $start, ?int $length = null): array
    {
        if (!is_numeric($start)) {
            throw new \InvalidArgumentException('Slice parameter 1, $start, must be an integer');
        }

        if ($length !== null && !is_numeric($length)) {
            throw new \InvalidArgumentException('Slice parameter 2, $length, must be null or an integer');
        }

        $maintainIndices = false;

        return array_slice($this->arr, $start, $length, $maintainIndices);
    }

}
