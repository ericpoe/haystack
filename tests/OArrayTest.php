<?php
namespace OPHP\Tests;

use OPHP\OArray;
use OPHP\OString;

class OArrayTest extends \PHPUnit_Framework_TestCase
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

    public function testCreateEmptyArray()
    {
        $array = new OArray();
        $this->assertEmpty($array);

        $emptyArr = array();
        $array = new OArray($emptyArr);
        $this->assertEmpty($array);
    }

    /**
     * @dataProvider goodArraysProvider
     *
     * @param $item
     */
    public function testCreateArrayOfThings($item)
    {
        $goodArr = new OArray($item);
        $this->assertArrayHasKey(0, $goodArr->toArray());
    }

    public function goodArraysProvider()
    {
        return [
            "bool: true" => [true],
            "bool: false" => [false],
            "integer" => [5],
            "integer: 0" => [0],
            "array" => [1, 2, 3],
            "ArrayObject" => [new \ArrayObject([0, 1, 2])],
            "string" => ["a"],
            "OString" => [new OString("a string")],
            "OString of OString of ... " => [new OString(new OString(new OString(new OString("a string"))))],
        ];
    }

    /**
     * @dataProvider badArraysProvider
     *
     * @param $item
     * @param $exceptionMsg
     */
    public function testCannotCreateArrayOfBadThings($item, $exceptionMsg)
    {
        $this->expectException("ErrorException");
        $this->expectExceptionMessage($exceptionMsg);

        $badArr = new OArray($item);
    }

    public function badArraysProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime cannot be instantiated as an OArray"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList cannot be instantiated as an OArray"],
        ];
    }

    public function testArrayStyleAccess()
    {
        $this->assertEquals("bobble", $this->arrList[1]);
        $this->assertEquals("bobble", $this->arrDict["b"]);
    }

    public function testArrayMap()
    {
        $capitalize = function ($word) {
            return strtoupper($word);
        };

        $newArr = $this->arrList->map($capitalize);

        $this->assertEquals("APPLE", $newArr[0]);
    }

    public function testArrayWalk()
    {
        $capitalizeDict = function ($word, $key) {
            return $this->arrDict[$key] = strtoupper($word);
        };

        $capitalizeList = function ($word, $key) {
            return $this->arrList[$key] = strtoupper($word);
        };

        $this->arrDict->walk($capitalizeDict);
        $this->assertEquals("APPLE", $this->arrDict["a"]);

        $this->arrList->walk($capitalizeList);
        $this->assertEquals("APPLE", $this->arrList[0]);
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

    public function testArrayHead()
    {
        $this->assertEquals(new OArray(["apple"]), $this->arrList->head());
        $this->assertEquals(new OArray(["a" => "apple"]), $this->arrDict->head());
    }

    public function testArrayTail()
    {
        $this->assertEquals(new OArray(["bobble", "cobble", "dobble"]), $this->arrList->tail());
        $this->assertEquals(new OArray(["b" => "bobble", "c" => "cobble", "d" => "dobble"]), $this->arrDict->tail());
    }

    /**
     * @dataProvider arraySumProvider
     *
     * @param \OPHP\OArray $testArr
     * @param              $expected
     */
    public function testArraySum(OArray $testArr, $expected)
    {
        $this->assertEquals($expected, $testArr->sum());
    }

    public function arraySumProvider()
    {
        return [
            "Empty OArray" => [new OArray(), 0],
            "List: Array of Strings" => [new OArray($this->arrList), 0],
            "List: Array of Strings & Int" => [new OArray(["apple", "bobble", "cobble", 5]), 5],
            "Dictionary: Array of Strings" => [new OArray($this->arrDict), 0],
            "Dictionary: Array of Strings & Int" => [new OArray(["a" => "apple", "b" => "bobble", "c" => "5"]), 5],
            "List: Array of Ints" => [new OArray(range(1, 10)), 55],
            "List: Array of Ints and String Ints" => [new OArray(["1", 2, "3", 4, "5", 6, "7", 8, "9", 10]), 55],
            "Dictionary: Array of Ints" => [new OArray(["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5, "f" => 6, "g" => 7, "h" => 8, "i" => 9, "j" => 10]), 55],
        ];
    }

    /**
     * @dataProvider arrayProductProvider()
     *
     * @param \OPHP\OArray $testArr
     * @param              $expected
     */
    public function testArrayProduct(OArray $testArr, $expected)
    {
        $this->assertEquals($expected, $testArr->product());
    }

    public function arrayProductProvider()
    {
        return [
            "Empty OArray" => [new OArray(), 0],
            "List: Array of Strings" => [new OArray("apple", "bobble", "cobble"), 0],
            "List: Array of Ints" => [new OArray([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]), 3628800],
            "List: Array of Strings & Int" => [new OArray(["apple", "bobble", "cobble", 5]), 0],
            "List: Array of String Ints & Int" => [new OArray(["1", 2, "3", 4, "5", 6, "7", 8, "9", 10]), 3628800],
            "Dictionary: Array of Strings" => [new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble"]), 0],
            "Dictionary: Array of Strings & Int" => [new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "5"]), 0],
            "Dictionary: Array of Ints" => [new OArray(["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5, "f" => 6, "g" => 7, "h" => 8, "i" => 9, "j" => 10]), 3628800],
        ];
    }
}
