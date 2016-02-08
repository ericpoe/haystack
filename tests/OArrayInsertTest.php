<?php
namespace OPHP\Tests;

use OPHP\OArray;
use OPHP\OString;

class OArrayInsertTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider arrayInsertProvider
     * @param $type
     * @param $babyArray
     * @param $key
     * @param $expected
     */
    public function testTypesOfArrayInsert($type, $babyArray, $key, $expected)
    {
        if ("list" === $type) {
            $newString = $this->arrList->insert($babyArray, $key);
        } else {
            $newString = $this->arrDict->insert($babyArray, $key);
        }

        $this->assertEquals($expected, $newString);
    }

    public function arrayInsertProvider()
    {
        return [
            "List: Int at end" => ["list", 5, null, new OArray(["apple", "bobble", "cobble", "dobble", 5])],
            "List: String array at 1" => ["list", ["foo"], "1", new OArray(["apple", "foo", "bobble", "cobble", "dobble"])],
            "List: String ArrayObject at 1" => ["list", new \ArrayObject(["foo"]), "1", new OArray(["apple", "foo", "bobble", "cobble", "dobble"])],
            "List: OString at 1" => ["list", [new OString("foo")], "1", new OArray(["apple", "foo", "bobble", "cobble", "dobble"])],
            "List: OString at -1" => ["list", [new OString("foo")], "-1", new OArray(["apple", "bobble", "cobble", "foo", "dobble"])],
            "Dictionary: Int at end" => ["dict", 5, null, new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", 0 => 5])],
            "Dictionary: String OArray at end" => ["dict", new OArray(["f" => "foo"]), null, new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", "f" => "foo"])],
            "Dictionary: OString at end" => ["dict", new OString("foo"), null, new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", "0" => "foo"])],
            "Dictionary: OArray dictionary at end" => ["dict", new OArray(["f" => "foo", "e" => "ebble"]), null, new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", "e" => "ebble", "f" => "foo"])],
            "Dictionary: OArray dictionary with matching key" => ["dict", new OArray(["b" => "foo"]), null, new OArray(["a" => "apple", "b" => ["bobble", "foo"], "c" => "cobble", "d" => "dobble"])],
            "Dictionary: ArrayObject dictionary with matching key" => ["dict", new \ArrayObject(["b" => "foo"]), null, new OArray(["a" => "apple", "b" => ["bobble", "foo"], "c" => "cobble", "d" => "dobble"])],
            "Dictionary: OString at present key" => ["dict", new OString("foo"), "b", new OArray(["a" => "apple", "b" => ["bobble", "foo"], "c" => "cobble", "d" => "dobble"])],
        ];
    }

    /**
     * @dataProvider badArrayInsertProvider
     *
     * @param $item
     * @param $exceptionMsg
     */
    public function testInsertBadThingsInOArray($item, $exceptionMsg)
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $this->arrList->insert($item);
    }

    public function badArrayInsertProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime cannot be contained within an OArray"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList cannot be contained within an OArray"],
        ];
    }

}
