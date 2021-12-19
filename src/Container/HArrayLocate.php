<?php

declare(strict_types=1);

namespace Haystack\Container;

use Haystack\HArray;
use Haystack\Helpers\Helper;

class HArrayLocate
{
    /** @var HArray */
    private $arr;

    public function __construct(HArray $arr)
    {
        $this->arr = $arr;
    }

    /**
     * @param mixed $value
     * @return int|string
     * @throws ElementNotFoundException
     */
    public function locate($value)
    {
        $foundItem = array_search($value, $this->arr->toArray(), true);

        if (false !== $foundItem) {
            return $foundItem;
        }

        $stringValue = Helper::getType($value);
        if (
            is_string($value) ||
            is_numeric($value) ||
            method_exists($value, '__toString')
        ) {
            $stringValue = (string) $value;
        }

        throw new ElementNotFoundException($stringValue);
    }
}
