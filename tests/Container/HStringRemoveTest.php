<?php
namespace Haystack\Tests\Container;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Haystack\HString;

class HStringRemoveTest extends \PHPUnit_Framework_TestCase
{
    /** @var HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString("foobar");
    }

    public function testTypesOfStringRemove()
    {
        $newString = $this->aString->remove("o");
        $this->assertEquals(new HString("fobar"), $newString);
    }

    public function testCannotRemoveBadString()
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage("DateTime is neither a scalar value nor an HString");

        $this->aString->remove(new \DateTime());
    }
}
