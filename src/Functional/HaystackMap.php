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

        return call_user_func_array('array_map', array_merge([$func], $sourceHaystack, $arrayOfVariadics));
    }

    private function convertToArray($item)
    {
        if (is_string($item)) {
            return (new HString($item))->toArray();
        }

        if ($item instanceof ContainerInterface) {
            return $item->toArray();
        }

        if (is_array($item)) {
            return $item;
        }

        throw new \InvalidArgumentException(Helper::getType($item) . " cannot be mapped");
    }
}
