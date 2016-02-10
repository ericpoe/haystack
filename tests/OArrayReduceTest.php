<?php
namespace OPHP\Tests;

use OPHP\OArray;
use OPHP\OString;

class OArrayReduceTest extends \PHPUnit_Framework_TestCase
{
    /** @var OArray */
    private $arrList;
    /** @var OArray */
    private $arrDict;

    protected function setUp()
    {
        $this->arrList = new OArray(["apple", "bobble", "cobble", "dobble"]);
        $this->arrDict = new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble"]);
    }

    /**
     * @dataProvider arrayReduceProvider
     *
     * @param OArray    $testArr
     * @param int       $expected
     */
    public function testArrayReduce(OArray $testArr, $expected)
    {
        $sum = function ($carry, $item) {
            $carry += $item;
            return $carry;
        };

        $this->assertEquals($expected, $testArr->reduce($sum));
    }

    public function arrayReduceProvider()
    {
        return [
            "Empty Array" => [new OArray(), 0],
            "List: Array of Strings" => [new OArray($this->arrList), 0],
            "List: Array of Strings & Int" => [new OArray(["apple", "bobble", "cobble", 5]), 5],
            "List: Array of Int" => [new OArray([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]), 55],
            "List: Array of Int & Int Strings" => [new OArray(["1", 2, "3", 4, "5", 6, "7", 8, "9", 10]), 55],
            "Dictionary: Array of Strings" => [new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble"]), 0],
            "Dictionary: Array of Strings & Int" => [new OArray(["a" => "apple", "b" => "bobble", "c" => "5"]), 5],
            "Dictionary: Array of Int" => [new OArray(["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5, "f" => 6, "g" => 7, "h" => 8, "i" => 9, "j" => 10]), 55],
            "Dictionary: Array of Int & Int Strings" => [new OArray(["a" => 1, "b" => "2", "c" => 3, "d" => "4", "e" => 5, "f" => "6", "g" => 7, "h" => "8", "i" => 9, "j" => "10"]), 55],
        ];
    }

    /**
     * @dataProvider arrayReduceWithInitProvider
     *
     * @param OArray       $testArr
     * @param int          $init
     * @param int          $expected
     */
    public function testArrayReduceWithInit(OArray $testArr, $init, $expected)
    {
        $sum = function ($carry, $item) {
            $carry += $item;
            return $carry;
        };

        $this->assertEquals($expected, $testArr->reduce($sum, $init));
    }

    public function arrayReduceWithInitProvider()
    {
        $fullArr = new OArray([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        return [
            "Full array with negative init" => [$fullArr, -10, 45],
            "Full array with positive init" => [$fullArr, 10, 65],
            "Empty array with negative init" => [new OArray(), -10, -10],
            "Empty array with positive init" => [new OArray(), 10, 10],
        ];
    }

    /**
     * @dataProvider reduceAsArrayTypeProvider
     *
     * @param $freq
     */
    public function testReduceAsArrayType($freq)
    {
        $this->assertTrue($this->arrList->reduce($freq) instanceof OArray);
        $this->assertTrue($this->arrDict->reduce($freq) instanceof OArray);
    }

    public function reduceAsArrayTypeProvider()
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
            "Array" => [$freqArray],
            "ArrayObject" => [$freqArrayObject],
            "OArray" => [$freqOArray],
        ];
    }

    public function testReduceAsString()
    {
        $toString = function ($sentence, $word) {
            $builtSentence = $sentence . $word . " ";
            return $builtSentence;
        };

        $this->assertEquals(new OString("apple bobble cobble dobble"), trim($this->arrList->reduce($toString)));
        $this->assertEquals(new OString("apple bobble cobble dobble"), trim($this->arrDict->reduce($toString)));
        $this->assertTrue($this->arrList->reduce($toString) instanceof OString);
        $this->assertTrue($this->arrDict->reduce($toString) instanceof OString);
    }
}
