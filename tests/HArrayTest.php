<?php
namespace Haystack\Tests;

use Haystack\HArray;
use Haystack\HString;

class HArrayTest extends \PHPUnit_Framework_TestCase
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

    public function testCreateEmptyArray()
    {
        $array = new HArray();
        $this->assertEmpty($array);

        $emptyArr = array();
        $array = new HArray($emptyArr);
        $this->assertEmpty($array);
    }

    public function testMake()
    {
        $this->assertInstanceOf("Haystack\HArray", HArray::make(["apple"]));
    }

    /**
     * @dataProvider goodArraysProvider
     *
     * @param $item
     */
    public function testCreateArrayOfThings($item)
    {
        $goodArr = new HArray($item);
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
            "HString" => [new HString("a string")],
            "HString of HString of ... " => [new HString(new HString(new HString(new HString("a string"))))],
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

        new HArray($item);
    }

    public function badArraysProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime cannot be instantiated as an HArray"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList cannot be instantiated as an HArray"],
        ];
    }

    public function testArrayStyleAccess()
    {
        $this->assertEquals("bobble", $this->arrList[1]);
        $this->assertEquals("bobble", $this->arrDict["b"]);
    }

    public function testArrayHead()
    {
        $this->assertEquals(new HArray(["apple"]), $this->arrList->head());
        $this->assertEquals(new HArray(["a" => "apple"]), $this->arrDict->head());
    }

    public function testArrayTail()
    {
        $this->assertEquals(new HArray(["bobble", "cobble", "dobble"]), $this->arrList->tail());
        $this->assertEquals(new HArray(["b" => "bobble", "c" => "cobble", "d" => "dobble"]), $this->arrDict->tail());
    }

    /**
     * @dataProvider arraySumProvider
     *
     * @param \Haystack\HArray $testArr
     * @param              $expected
     */
    public function testArraySum(HArray $testArr, $expected)
    {
        $this->assertEquals($expected, $testArr->sum());
    }

    public function arraySumProvider()
    {
        return [
            "Empty HArray" => [new HArray(), 0],
            "List: Array of Strings" => [new HArray($this->arrList), 0],
            "List: Array of Strings & Int" => [new HArray(["apple", "bobble", "cobble", 5]), 5],
            "Dictionary: Array of Strings" => [new HArray($this->arrDict), 0],
            "Dictionary: Array of Strings & Int" => [new HArray(["a" => "apple", "b" => "bobble", "c" => "5"]), 5],
            "List: Array of Ints" => [new HArray(range(1, 10)), 55],
            "List: Array of Ints and String Ints" => [new HArray(["1", 2, "3", 4, "5", 6, "7", 8, "9", 10]), 55],
            "Dictionary: Array of Ints" => [new HArray(["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5, "f" => 6, "g" => 7, "h" => 8, "i" => 9, "j" => 10]), 55],
        ];
    }

    /**
     * @dataProvider arrayProductProvider()
     *
     * @param \Haystack\HArray $testArr
     * @param              $expected
     */
    public function testArrayProduct(HArray $testArr, $expected)
    {
        $this->assertEquals($expected, $testArr->product());
    }

    public function arrayProductProvider()
    {
        return [
            "Empty HArray" => [new HArray(), 0],
            "List: Array of Strings" => [new HArray("apple", "bobble", "cobble"), 0],
            "List: Array of Ints" => [new HArray([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]), 3628800],
            "List: Array of Strings & Int" => [new HArray(["apple", "bobble", "cobble", 5]), 0],
            "List: Array of String Ints & Int" => [new HArray(["1", 2, "3", 4, "5", 6, "7", 8, "9", 10]), 3628800],
            "Dictionary: Array of Strings" => [new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble"]), 0],
            "Dictionary: Array of Strings & Int" => [new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "5"]), 0],
            "Dictionary: Array of Ints" => [new HArray(["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5, "f" => 6, "g" => 7, "h" => 8, "i" => 9, "j" => 10]), 3628800],
        ];
    }
}
