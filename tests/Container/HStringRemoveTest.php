<?php
namespace Haystack\Tests\Container;

use Haystack\HString;
use PHPUnit\Framework\TestCase;

class HStringRemoveTest extends TestCase
{
    /** @var HString */
    protected $aString;

    /** @var HString */
    protected $utf8String;

    protected function setUp()
    {
        $this->aString = new HString('foobar');
        $this->utf8String = new HString('ɹɐqooɟ');
    }

    public function testTypesOfStringRemove()
    {
        $newString = $this->aString->remove('o');
        $this->assertEquals(new HString('fobar'), $newString);

        $newString = $this->utf8String->remove('o');
        $this->assertEquals(new HString('ɹɐqoɟ'), $newString);
    }

    public function testCannotRemoveBadString()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('DateTime cannot be converted to a string; it cannot be used as a search value within an HString');

        $this->aString->remove(new \DateTime());
    }
}
