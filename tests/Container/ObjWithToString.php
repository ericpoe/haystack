<?php
namespace Haystack\Tests\Container;

/**
 * Class ObjWithToString
 *
 * This is a single-use object that would be better served as a PHP7 anonymous class.
 */
class ObjWithToString
{
    public function __toString()
    {
        return sprintf("I'm a string");
    }
}
