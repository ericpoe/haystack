<?php
namespace OPHP\Tests;

use OPHP\OString;

class OStringWalkTest extends \PHPUnit_Framework_TestCase
{
    /** @var OString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new OString("foobar");
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
