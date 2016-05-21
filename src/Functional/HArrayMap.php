<?php
namespace Haystack\Functional;

use Haystack\Container\ContainerInterface;
use Haystack\HArray;

class HArrayMap
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
        $arrays = [$this->arr];

        foreach ($variadicList as $item) {
            if ($item instanceof ContainerInterface) {
                $item = $item->toArray();
            }

            if (is_array($item)) {
                $arrays[] = $item;
            } else {
                throw new \InvalidArgumentException("{$item} cannot be mapped");
            }
        }

        return call_user_func_array('array_map', array_merge([$func], $arrays));
    }
}
