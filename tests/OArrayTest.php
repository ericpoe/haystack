<?php
namespace OPHP\Test;

use OPHP\OArray;
use OPHP\OString;

class OArrayTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \OPHP\OArray */
    private $arrList;
    /** @var  \OPHP\OArray */
    private $arrDict;

    protected function setUp()
    {
        $this->arrList = new OArray(array("apple", "bobble", "cobble", "dobble"));
        $this->arrDict = new OArray(array("a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble"));
    }

    /**
     * @dataProvider arrayContainsProvider
     *
     * @param $type
     * @param $checkThing
     * @param $expected
     */
    public function testStringTypeInOArray($type, $checkThing, $expected)
    {
        if ("list" == $type) {
            $bool = $this->arrList->contains($checkThing);
        } else {
            $bool = $this->arrDict->contains($checkThing);
        }
        $expected ? $this->assertTrue($bool) : $this->assertFalse($bool);
    }

    public function arrayContainsProvider()
    {
        return [
            ["type" => "list", "checkThing" => "apple", "expected" => true],
            ["type" => "list", "checkThing" => "cobble", "expected" => true],
            ["type" => "list", "checkThing" => "fobble", "expected" => false],
            ["type" => "list", "checkThing" => 3, "expected" => false],
            ["type" => "dict", "checkThing" => "apple", "expected" => true],
            ["type" => "dict", "checkThing" => "cobble", "expected" => true],
            ["type" => "dict", "checkThing" => "fobble", "expected" => false],
            ["type" => "dict", "checkThing" => 3, "expected" => false],
        ];
    }


    /**
     * @dataProvider arrayLocateProvider
     *
     * @param $checkThing
     * @param $expected
     */
    public function testLocateStringTypeInOArray($checkThing, $expected)
    {
        $var = $this->arrList->locate($checkThing);
        $this->assertEquals($expected, $var);
    }

    public function arrayLocateProvider()
    {
        return [
            ["checkThing" => "apple", "expected" => 0],
            ["checkThing" => "fobble", "expected" => -1],
            ["checkThing" => new OString("apple"), "expected" => 0],
            ["checkThing" => new OString("fobble"), "expected" => -1],
        ];
    }

    /**
     * @dataProvider appendProvider
     *
     * @param $type
     * @param $newThing
     * @param $expected
     */
    public function testAppendStringInArray($type, $newThing, $expected)
    {
        if ("list" === $type) {
            $newArray = $this->arrList->append($newThing);
        } else {
            $newArray = $this->arrDict->append($newThing);
        }

        $this->assertEquals($expected, $newArray);
    }

    public function appendProvider()
    {
        return [
            ["type" => "list", "newThing" => "ebble", "expected" => new OArray(["apple", "bobble", "cobble", "dobble", "ebble"])],
            ["type" => "dict", "newThing" => ["e" => "ebble"], "expected" => new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", ["e" => "ebble"]])],
        ];
    }

    /**
     * @dataProvider firstPartOfArraySliceProvider
     *
     * @param $type
     * @param $expected
     */
    public function testGetFirstPartOfTypesOfArrayUsingSlice($type, $expected)
    {
        if ("list" === $type) {
            $subArray = $this->arrList->slice(0, 2);
        } else {
            $subArray = $this->arrDict->slice(0, 2);
        }

        $this->assertEquals($expected, $subArray);
    }

    public function firstPartOfArraySliceProvider()
    {
        return [
            ["type" => "list", "expected" => new OArray(["apple", "bobble"])],
            ["type" => "dict", "expected" => new OArray(["a" => "apple", "b" => "bobble"])],
        ];
    }

    /**
     * @dataProvider lastPartOfArraySliceProvider
     *
     * @param $type
     * @param $expected
     */
    public function testGetLastPartOfTypesOfArrayUsingSlice($type, $expected)
    {
        if ("list" === $type) {
            $subArray = $this->arrList->slice(-2);
        } else {
            $subArray = $this->arrDict->slice(-2);
        }

        $this->assertEquals($expected, $subArray);
    }

    public function lastPartOfArraySliceProvider()
    {
        return [
            ["type" => "list", "expected" => new OArray(["cobble", "dobble"])],
            ["type" => "dict", "expected" => new OArray(["c" => "cobble", "d" => "dobble"])],
        ];
    }

    /**
     * @dataProvider middlePartOfArraySliceProvider
     *
     * @param $type
     * @param $expected
     */
    public function testGetMiddlePartOfTypesOfArrayUsingSlice($type, $expected)
    {
        if ("list" === $type) {
            $subArray = $this->arrList->slice(-3, -1);
        } else {
            $subArray = $this->arrDict->slice(-3, -1);
        }

        $this->assertEquals($expected, $subArray);
    }

    public function middlePartOfArraySliceProvider()
    {
        return [
            ["type" => "list", "expected" => new OArray(["bobble", "cobble"])],
            ["type" => "dict", "expected" => new OArray(["b"=> "bobble", "c" => "cobble"])],
        ];
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
            [
                "type" => "list",
                "babyArray" => ["foo"],
                "key" => "1",
                "expected" => new OArray(["apple", "foo", "bobble","cobble", "dobble"])
            ],
            [
                "type" => "list",
                "babyArray" => [new OString("foo")],
                "key" => "1",
                "expected" => new OArray(["apple", "foo", "bobble","cobble", "dobble"])
            ],
            [
                "type" => "dict",
                "babyArray" => new OArray(["f" => "foo"]),
                "key" => null,
                "expected" => new OArray(["a" => "apple", "b" => "bobble", "c"=> "cobble", "d" => "dobble", "f" => "foo"])
            ],
            [
                "type" => "dict",
                "babyArray" => new OString("foo"),
                "key" => null,
                "expected" => new OArray(["a" => "apple", "b" => "bobble", "c"=> "cobble", "d" => "dobble", "0" => "foo"])
            ],
            [
                "type" => "dict",
                "babyArray" => new OArray(["f" => "foo", "e" => "ebble"]),
                "key" => null,
                "expected" => new OArray(["a" => "apple", "b" => "bobble", "c"=> "cobble", "d" => "dobble", "e" => "ebble", "f" => "foo"])
            ],
            [
                "type" => "dict",
                "babyArray" => new OArray(["b" => "foo"]),
                "key" => null,
                "expected" => new OArray(["a" => "apple", "b" => ["bobble", "foo"], "c"=> "cobble", "d" => "dobble"]),
            ],
            [
                "type" => "dict",
                "babyArray" => new OString("foo"),
                "key" => "b",
                "expected" => new OArray(["a" => "apple", "b" => ["bobble", "foo"], "c"=> "cobble", "d" => "dobble"]),
            ],
        ];
    }

    /**
     * @expectedException \ErrorException
     */
    public function testObjectCannotBeUsedAsArrayKey()
    {
        $newArray = $this->arrDict->insert("yobbo", new \DateTime());
    }
}
