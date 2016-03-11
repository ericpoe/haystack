<?php
namespace Haystack\Tests\Container;

use Haystack\HArray;
use Haystack\HString;

class HArrayInsertTest extends \PHPUnit_Framework_TestCase
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
            "List: Int at end" => ["list", 5, null, new HArray(["apple", "bobble", "cobble", "dobble", 5])],
            "List: String array at 1" => ["list", ["foo"], "1", new HArray(["apple", "foo", "bobble", "cobble", "dobble"])],
            "List: String ArrayObject at 1" => ["list", new \ArrayObject(["foo"]), "1", new HArray(["apple", "foo", "bobble", "cobble", "dobble"])],
            "List: HString at 1" => ["list", [new HString("foo")], "1", new HArray(["apple", "foo", "bobble", "cobble", "dobble"])],
            "List: HString at -1" => ["list", [new HString("foo")], "-1", new HArray(["apple", "bobble", "cobble", "foo", "dobble"])],
            "Dictionary: Int at end" => ["dict", 5, null, new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", 0 => 5])],
            "Dictionary: String HArray at end" => ["dict", new HArray(["f" => "foo"]), null, new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", "f" => "foo"])],
            "Dictionary: HString at end" => ["dict", new HString("foo"), null, new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", "0" => "foo"])],
            "Dictionary: HArray dictionary at end" => ["dict", new HArray(["f" => "foo", "e" => "ebble"]), null, new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", "e" => "ebble", "f" => "foo"])],
            "Dictionary: HArray dictionary with matching key" => ["dict", new HArray(["b" => "foo"]), null, new HArray(["a" => "apple", "b" => ["bobble", "foo"], "c" => "cobble", "d" => "dobble"])],
            "Dictionary: ArrayObject dictionary with matching key" => ["dict", new \ArrayObject(["b" => "foo"]), null, new HArray(["a" => "apple", "b" => ["bobble", "foo"], "c" => "cobble", "d" => "dobble"])],
            "Dictionary: HString at present key" => ["dict", new HString("foo"), "b", new HArray(["a" => "apple", "b" => ["bobble", "foo"], "c" => "cobble", "d" => "dobble"])],
        ];
    }

    public function testInsertObjects()
    {
        $object1 = new \DateTime();
        $arrList = $this->arrList->insert($object1);
        $this->assertTrue($arrList->contains($object1));
        $this->assertEquals(4, $arrList->locate($object1));
    }

    /**
     * @dataProvider badInsertKeyProvider
     *
     * @param $key
     * @param $exceptionMsg
     */
    public function testObjectCannotBeUsedAsArrayKey($key, $exceptionMsg)
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $this->arrDict->insert("yobbo", $key);
    }

    public function badInsertKeyProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "Invalid array key"],
            "SPL Object" => [new \SplDoublyLinkedList(), "Invalid array key"],
        ];
    }

}
