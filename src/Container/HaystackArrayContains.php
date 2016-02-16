<?php
namespace Haystack\Container;

use Haystack\Helpers\ArrayHelper;
use Haystack\Helpers\Helper;
use Haystack\HArray;

class HaystackArrayContains
{
    /**
     * @var HArray
     */
    private $arr;

    /** @var Helper */
    private $helper;

    public function __construct(HArray $arr)
    {
        $this->arr = $arr;
        $this->helper = new Helper();
    }

    /**
     * @param $value
     * @return bool
     */
    public function contains($value)
    {
        if (ArrayHelper::canBeInArray($value)) {
            $arr = $this->arr->toArray();
            $answer = in_array($value, $arr);
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} cannot be contained within an HArray");
        }
        return $answer;
    }
}
