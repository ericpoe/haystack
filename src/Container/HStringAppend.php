<?php
declare(strict_types=1);

namespace Haystack\Container;

use Haystack\HaystackInterface;
use Haystack\Helpers\Helper;
use Haystack\HString;

class HStringAppend
{
    /** @var string */
    private $aString;

    public function __construct(HString $hString)
    {
        $this->aString = (string) $hString;
    }

    /**
     * @param HaystackInterface|int|float|string|array $value
     * @return string
     */
    public function append($value): string
    {
        if (is_scalar($value) || $value instanceof HaystackInterface) {
            return sprintf('%s%s', $this->aString, $value);
        }
        throw new \InvalidArgumentException(sprintf('Cannot concatenate an HString with a %s', Helper::getType($value)));
    }
}
