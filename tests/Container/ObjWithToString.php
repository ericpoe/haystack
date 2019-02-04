<?php
declare(strict_types=1);

namespace Haystack\Tests\Container;

/**
 * Class ObjWithToString
 *
 * This is a single-use object that would be better served as a PHP7 anonymous class.
 */
class ObjWithToString
{
    public function __toString(): string
    {
        return sprintf("I'm a string");
    }
}
