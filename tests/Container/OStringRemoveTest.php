<?php
namespace OPHP\Tests\Container;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use OPHP\OString;

class OStringRemoveTest extends \PHPUnit_Framework_TestCase
{
    /** @var OString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new OString("foobar");
    }

    public function testTypesOfStringRemove()
    {
        $newString = $this->aString->remove("o");
        $this->assertEquals(new OString("fobar"), $newString);
    }

    public function testCannotRemoveBadString()
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage("DateTime is neither a scalar value nor an OString");

        $this->aString->remove(new \DateTime());
    }
}
