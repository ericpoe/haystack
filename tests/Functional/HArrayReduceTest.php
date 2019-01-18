<?php
namespace Haystack\Tests\Functional;

use Haystack\HArray;
use Haystack\HString;
use PHPUnit\Framework\TestCase;

class HArrayReduceTest extends TestCase
{
    /** @var HArray */
    private $arrList;
    /** @var HArray */
    private $arrDict;

    protected function setUp()
    {
        $this->arrList = new HArray(["apple", "bobble", "cobble", "dobble"]);
        $this->arrDict = new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble"]);
    }

    /**
     * @dataProvider arrayReduceProvider
     *
     * @param HArray    $testArr
     * @param int       $expected
     */
    public function testArrayReduce(HArray $testArr, $expected)
    {
        $sum = function ($carry, $item) {
            $carry += (int) $item;
            return $carry;
        };

        $this->assertEquals($expected, $testArr->reduce($sum));
    }

    public function arrayReduceProvider()
    {
        return [
            "Empty Array" => [new HArray(), 0],
            "List: Array of Strings" => [new HArray($this->arrList), 0],
            "List: Array of Strings & Int" => [new HArray(["apple", "bobble", "cobble", 5]), 5],
            "List: Array of Int" => [new HArray([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]), 55],
            "List: Array of Int & Int Strings" => [new HArray(["1", 2, "3", 4, "5", 6, "7", 8, "9", 10]), 55],
            "Dictionary: Array of Strings" => [new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble"]), 0],
            "Dictionary: Array of Strings & Int" => [new HArray(["a" => "apple", "b" => "bobble", "c" => "5"]), 5],
            "Dictionary: Array of Int" => [new HArray(["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5, "f" => 6, "g" => 7, "h" => 8, "i" => 9, "j" => 10]), 55],
            "Dictionary: Array of Int & Int Strings" => [new HArray(["a" => 1, "b" => "2", "c" => 3, "d" => "4", "e" => 5, "f" => "6", "g" => 7, "h" => "8", "i" => 9, "j" => "10"]), 55],
        ];
    }

    /**
     * @dataProvider arrayReduceWithInitProvider
     *
     * @param HArray       $testArr
     * @param int          $init
     * @param int          $expected
     */
    public function testArrayReduceWithInit(HArray $testArr, $init, $expected)
    {
        $sum = function ($carry, $item) {
            $carry += $item;
            return $carry;
        };

        $this->assertEquals($expected, $testArr->reduce($sum, $init));
    }

    public function arrayReduceWithInitProvider()
    {
        $fullArr = new HArray([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        return [
            "Full array with negative init" => [$fullArr, -10, 45],
            "Full array with positive init" => [$fullArr, 10, 65],
            "Empty array with negative init" => [new HArray(), -10, -10],
            "Empty array with positive init" => [new HArray(), 10, 10],
        ];
    }

    /**
     * @dataProvider reduceAsArrayTypeProvider
     *
     * @param $freq
     */
    public function testReduceAsArrayType($freq)
    {
        $this->assertTrue($this->arrList->reduce($freq) instanceof HArray);
        $this->assertTrue($this->arrDict->reduce($freq) instanceof HArray);
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

        $freqHArray = function ($frequency, $letter) {
            if (!isset($frequency[$letter])) {
                $frequency[$letter] = 0;
            }

            $frequency = new HArray($frequency);

            $frequency[$letter]++;

            return $frequency;
        };

        return [
            "Array" => [$freqArray],
            "ArrayObject" => [$freqArrayObject],
            "HArray" => [$freqHArray],
        ];
    }

    public function testReduceAsString()
    {
        $toString = function ($sentence, $word) {
            $builtSentence = $sentence . $word . " ";
            return $builtSentence;
        };

        $this->assertEquals(new HString("apple bobble cobble dobble"), trim($this->arrList->reduce($toString)));
        $this->assertEquals(new HString("apple bobble cobble dobble"), trim($this->arrDict->reduce($toString)));
        $this->assertTrue($this->arrList->reduce($toString) instanceof HString);
        $this->assertTrue($this->arrDict->reduce($toString) instanceof HString);
    }
}
