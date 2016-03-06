<?php
namespace Haystack\Container;

use Haystack\Helpers\ArrayHelper;
use Haystack\Helpers\Helper;
use Haystack\HArray;

class HaystackArrayAppend
{
    /** @var \ArrayObject */
    private $arr;

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->arr = new \ArrayObject($array);
    }

    /**
     * @param HArray|array|numeric|string $value
     * @return \ArrayObject
     */
    public function append($value)
    {
        $value = $value instanceof HArray ? $value->toArray() : $value;

        if (ArrayHelper::canBeInArray($value)) {
            $this->arr->append($value);

            return $this->arr;
        } else {
            throw new \InvalidArgumentException(sprintf("%s cannot be appended to an HArray", Helper::getType($value)));
        }
    }

}
