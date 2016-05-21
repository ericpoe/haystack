<?php
namespace Haystack\Functional;

use Haystack\Container\ContainerInterface;
use Haystack\HArray;
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
        if (empty($variadicList)) {
            return $this->hString->toHArray()->map($func)->toHString();
        }

        $arrays = [$this->hString->toArray()];

        foreach ($variadicList as $item) {
            if (is_string($item)) {
                $item = new HString($item);
            }

            if ($item instanceof ContainerInterface) {
                $item = $item->toArray();
            }

            if (is_array($item)) {
                $arrays[] = $item;
            } else {
                throw new \InvalidArgumentException("{$item} cannot be mapped");
            }
        }

        $result = call_user_func_array('array_map', array_merge([$func], $arrays));

        return (new HArray($result))->toHString();
    }
}
