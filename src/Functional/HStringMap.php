<?php
namespace Haystack\Functional;

use Haystack\Container\ContainerInterface;
use Haystack\HArray;
use Haystack\Helpers\Helper;
use Haystack\HString;

class HStringMap
{
    /** @var HString */
    private $hString;

    /**
     * @param HString $hString
     */
    public function __construct(HString $hString)
    {
        $this->hString = $hString;
    }

    /**
     * @param callable $func
     * @param array    $variadicList
     * @return HString
     */
    public function map(callable $func, $variadicList = [])
    {
        $sourceHaystack = [$this->hString->toArray()];

        $arrayOfVariadics = array_map(function ($item) {
            return $this->convertToArray($item);
        }, $variadicList);

        $result = call_user_func_array('array_map', array_merge([$func], $sourceHaystack, $arrayOfVariadics));

        return (new HArray($result))->toHString();
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
