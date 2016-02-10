<?php
namespace OPHP\Tests;

use OPHP\OArray;
use OPHP\OString;

class OStringReduceTest extends \PHPUnit_Framework_TestCase
{
    /** @var OString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new OString("foobar");
    }

    public function testReduce()
    {
        $fn = function ($carry, $item) {
            $value = (ord(strtolower($item)) - 64);
            return $carry + $value;
        };

        $this->assertEquals(249, $this->aString->reduce($fn));
    }

    public function testOStringReduce()
    {
        $encode = function ($carry, $item) {
            $value = (ord($item) % 26) + 97;
            $carry .= chr($value);

            return $carry;
        };

        $decode = function ($carry, $item) {
            $value = ((ord($item) + 14) % 26) + 97;
            $carry .= chr($value);

            return $carry;
        };

        $codedMessage = new OString("yhhutk");

        $this->assertEquals($codedMessage, $this->aString->reduce($encode));
        $this->assertEquals("foobar", $codedMessage->reduce($decode));
        $this->assertTrue($this->aString->reduce($encode) instanceof OString);
    }

    /**
     * @dataProvider stringReduceAsArrayTypeProvider
     * @param $freq
     * @param string $message
     */
    public function testStringReduceAsArrayTypeReturnsOArray($freq, $message)
    {
        $this->assertTrue($this->aString->reduce($freq) instanceof OArray, $message);
    }

    public function stringReduceAsArrayTypeProvider()
    {
        $freqArray = function ($frequency, $letter) {
            if (!isset($frequency[$letter])) {
                $frequency[$letter] = 0;
            }

            $frequency[$letter]++;

            return $frequency;
        };

        $freqArrayObject = function ($frequency, $letter) {
            if (!isset($frequency[$letter])) {
                $frequency[$letter] = 0;
            }

            $frequency = new \ArrayObject($frequency);

            $frequency[$letter]++;

            return $frequency;
        };

        $freqOArray = function ($frequency, $letter) {
            if (!isset($frequency[$letter])) {
                $frequency[$letter] = 0;
            }

            $frequency = new OArray($frequency);

            $frequency[$letter]++;

            return $frequency;
        };

        return [
            "Array" => [$freqArray, "An Array"],
            "ArrayObject" => [$freqArrayObject, "An ArrayObject"],
            "OArray" => [$freqOArray, "An OArray"],
        ];
    }

    /**
     * @dataProvider stringReduceWithInitialValueProvider
     *
     * @param OString $string
     * @param $initial
     * @param $expected
     */
    public function testStringReduceWithInitialValue(OString $string, $initial, $expected)
    {
        $what = function ($carry, $item) {
            $carry .= $item;

            return $carry;
        };

        $this->assertEquals($expected, $string->reduce($what, $initial));
    }

    public function stringReduceWithInitialValueProvider()
    {
        return [
            "Empty OString" => [new OString(), "alone", "alone"],
            "OString" => [new OString("present"), "The ", "The present"],
        ];
    }
}
