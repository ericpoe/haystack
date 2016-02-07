<?php


namespace OPHP\Tests;


use OPHP\OString;

class OStringFilterTest extends \PHPUnit_Framework_TestCase
{
    /** @var \OPHP\Ostring */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new OString("foobar");
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
            $vowels = new OString("aeiou");

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

        $flag = OString::USE_KEY;

        $even = $this->aString->filter($removeOdd, $flag);
        $this->assertEquals("obr", $even->toString(), "Filter by Key");

        $even = $this->aString->filter(function ($key) {
            return $key % 2;
        }, $flag);
        $this->assertEquals("obr", $even->toString(), "Filter by Key");
    }

    public function testStringValueAndKeyFilter()
    {

        $alpha = new OString('abcdefghijklmnopqrstuvwxyz');
        $evenAlpha = $alpha->filter(function ($key) {
            return $key % 2;
        }, OString::USE_KEY);

        $thingBoth = function ($letter, $key) use ($evenAlpha) {
            if ($evenAlpha->contains($letter)) {
                return true;
            }

            return $key % 2;
        };

        $flag = OString::USE_BOTH;
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
