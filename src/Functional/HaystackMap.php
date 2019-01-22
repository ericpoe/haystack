<?php
namespace Haystack\Functional;

use Haystack\Container\ContainerInterface;
use Haystack\HArray;
use Haystack\Helpers\Helper;
use Haystack\HString;

class HaystackMap
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

    /**
     * @param callable $func
     * @param array $variadicList Variadic list of arrays to invoke array_map with
     * @return array
     */
    public function map(callable $func, array $variadicList = [])
    {
        $sourceHaystack = [$this->arr];

        $arrayOfVariadics = array_map(function ($item) {
            return $this->convertToArray($item);
        }, $variadicList);

        return array_map(...array_merge([$func], $sourceHaystack, $arrayOfVariadics));
    }

    private function convertToArray($item)
    {
        if (is_array($item)) {
            return $item;
        }

        if (is_string($item)) {
            return (new HString($item))->toArray();
        }

        if ($item instanceof ContainerInterface) {
            return $item->toArray();
        }

        throw new \InvalidArgumentException(sprintf('%s cannot be mapped', Helper::getType($item)));
    }
}
