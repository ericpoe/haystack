<?php
namespace Haystack\Container;

use Haystack\Helpers\Helper;
use Haystack\HString;

class HaystackStringLocate
{
    /** @var Helper */
    private $helper;

    /** @var HString */
    private $string;

    public function __construct(HString $string)
    {
        $this->string = $string;
        $this->helper = new Helper();
    }

    /**
     * @param $value
     * @return int
     */
    public function locate($value)
    {
        if (is_scalar($value)) {
            return $this->string->contains($value) ? strpos($this->string, (string) $value) : -1;
        }

        if ($value instanceof HString) {
            return $this->string->contains($value) ? strpos($this->string, $value->toString()) : -1;
        }

        throw new \InvalidArgumentException("{$this->helper->getType($value)} is neither a scalar value nor an HString");
    }

}
