<?php
namespace Haystack\Tests\Functional;

use Haystack\HString;

class HStringFilterTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Haystack\HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString("foobar");
    }

    public function testStringDefaultFilter()
    {
        $strangeString = $this->aString->insert(0, 3);
        $default = $strangeString->filter();
        $this->assertEquals("foobar", $default->toString(), "Filter with defaults");
    }

    public function testStringValuesFilter()
    {
        $removeVowels = function ($letter) {
            $vowels = new HString("aeiou");

            return !$vowels->contains($letter);
        };

        $consonants = $this->aString->filter($removeVowels);
        $this->assertEquals("fbr", $consonants->toString(), "Filter by Value");
    }

    public function testStringKeyFilter()
    {
        $removeOdd = function ($key) {
            return $key % 2;
        };

        $flag = HString::USE_KEY;

        $even = $this->aString->filter($removeOdd, $flag);
        $this->assertEquals("obr", $even->toString(), "Filter by Key");

        $even = $this->aString->filter(function ($key) {
            return $key % 2;
        }, $flag);
        $this->assertEquals("obr", $even->toString(), "Filter by Key");
    }

    public function testStringValueAndKeyFilter()
    {

        $alpha = new HString('abcdefghijklmnopqrstuvwxyz');
        $evenAlpha = $alpha->filter(function ($key) {
            return $key % 2;
        }, HString::USE_KEY);

        $thingBoth = function ($letter, $key) use ($evenAlpha) {
            if ($evenAlpha->contains($letter)) {
                return true;
            }

            return $key % 2;
        };

        $flag = HString::USE_BOTH;
        $funky = $this->aString->filter($thingBoth, $flag);
        $this->assertEquals("fobr", $funky->toString(), "Filter by both Value & Key");
    }

    public function testInvalidFilterFlag()
    {
        $flag = "bad_flag";
        $exceptionMsg = "Invalid flag name";

        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);
        $even = $this->aString->filter(function ($key) {
            return $key % 2;
        }, $flag);
    }
}
