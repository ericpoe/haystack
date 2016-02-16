<?php
namespace Haystack\Tests\Functional;

use Haystack\HArray;
use Haystack\HString;

class HStringReduceTest extends \PHPUnit_Framework_TestCase
{
    /** @var HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString("foobar");
    }

    public function testReduce()
    {
        $fn = function ($carry, $item) {
            $value = (ord(strtolower($item)) - 64);
            return $carry + $value;
        };

        $this->assertEquals(249, $this->aString->reduce($fn));
    }

    public function testHStringReduce()
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

        $codedMessage = new HString("yhhutk");

        $this->assertEquals($codedMessage, $this->aString->reduce($encode));
        $this->assertEquals("foobar", $codedMessage->reduce($decode));
        $this->assertTrue($this->aString->reduce($encode) instanceof HString);
    }

    /**
     * @dataProvider stringReduceAsArrayTypeProvider
     * @param $freq
     * @param string $message
     */
    public function testStringReduceAsArrayTypeReturnsHArray($freq, $message)
    {
        $this->assertTrue($this->aString->reduce($freq) instanceof HArray, $message);
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

        $freqHArray = function ($frequency, $letter) {
            if (!isset($frequency[$letter])) {
                $frequency[$letter] = 0;
            }

            $frequency = new HArray($frequency);

            $frequency[$letter]++;

            return $frequency;
        };

        return [
            "Array" => [$freqArray, "An Array"],
            "ArrayObject" => [$freqArrayObject, "An ArrayObject"],
            "HArray" => [$freqHArray, "An HArray"],
        ];
    }

    /**
     * @dataProvider stringReduceWithInitialValueProvider
     *
     * @param HString $string
     * @param $initial
     * @param $expected
     */
    public function testStringReduceWithInitialValue(HString $string, $initial, $expected)
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
            "Empty HString" => [new HString(), "alone", "alone"],
            "HString" => [new HString("present"), "The ", "The present"],
        ];
    }
}
