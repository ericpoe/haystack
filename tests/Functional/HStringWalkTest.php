<?php
namespace Haystack\Tests\Functional;

use Haystack\HString;

class HStringWalkTest extends \PHPUnit_Framework_TestCase
{
    /** @var HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString("foobar");
    }

    public function testStringWalk()
    {
        $capitalize = function ($letter, $key) {
            return $this->aString[$key] = strtoupper($letter);
        };

        $this->aString->walk($capitalize);

        $this->assertEquals("FOOBAR", $this->aString->toString());
    }
}
