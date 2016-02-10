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
