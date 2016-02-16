<?php
namespace Haystack\Tests\Functional;

use Haystack\HString;

class HStringMapTest extends \PHPUnit_Framework_TestCase
{
    /** @var HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString("foobar");
    }

    public function testStringMap()
    {
        $capitalize = function ($letter) {
            return strtoupper($letter);
        };

        $newString = $this->aString->map($capitalize);

        $this->assertEquals("FOOBAR", $newString);
    }

    public function testStringMapRot13()
    {
        $rot13 = function ($letter) {
            if (" " === $letter || "-" === $letter) {
                return $letter;
            }

            return chr(97 + (ord($letter) - 97 + 13) % 26);
        };

        $newString = $this->aString->map($rot13);

        $expected = "sbbone";
        $this->assertEquals($expected, $newString);
    }
}
