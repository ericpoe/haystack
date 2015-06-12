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

    public function testCreateEmptyArray()
    {
        $array = new OArray();
        $this->assertEmpty($array);

        $emptyArr = array();
        $array = new OArray($emptyArr);
        $this->assertEmpty($array);
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
     * @param $start
     * @param $finish
     * @param $expected
     */
    public function testGetMiddlePartOfTypesOfArrayUsingSlice($type, $start, $finish, $expected)
    {
        if ("list" === $type) {
            $subArray = $this->arrList->slice($start, $finish);
        } else {
            $subArray = $this->arrDict->slice($start, $finish);
        }

        $this->assertEquals($expected, $subArray);
    }

    public function middlePartOfArraySliceProvider()
    {
        return [
            ["type" => "list", "start" => "-3", "finish" => "-1", "expected" => new OArray(["bobble", "cobble"])],
            ["type" => "list", "start" => "1", "finish" => "-1", "expected" => new OArray(["bobble", "cobble"])],
            ["type" => "list", "start" => "1", "finish" => "2", "expected" => new OArray(["bobble", "cobble"])],
            ["type" => "list", "start" => "1", "finish" => null, "expected" => new OArray(["bobble", "cobble", "dobble"])],
            ["type" => "dict", "start" => "-3", "finish" => "-1", "expected" => new OArray(["b"=> "bobble", "c" => "cobble"])],
            ["type" => "dict", "start" => "1", "finish" => "-1", "expected" => new OArray(["b"=> "bobble", "c" => "cobble"])],
            ["type" => "dict", "start" => "1", "finish" => "2", "expected" => new OArray(["b"=> "bobble", "c" => "cobble"])],
            ["type" => "dict", "start" => "1", "finish" => null, "expected" => new OArray(["b"=> "bobble", "c" => "cobble", "d" => "dobble"])],
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
                "type" => "list",
                "babyArray" => [new OString("foo")],
                "key" => "-1",
                "expected" => new OArray(["apple", "bobble","cobble", "foo", "dobble"])
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
     * @dataProvider arrayRemoveProvider
     *
     * @param $type
     * @param $value
     * @param $expected
     * @throws \ErrorException
     */
    public function testArrayTypeRemove($type, $value, $expected, $message)
    {
        if ("list" === $type) {
            $newArr = $this->arrList->remove($value);
        } else {
            $newArr = $this->arrDict->remove($value);
        }

        $this->assertEquals($expected, $newArr, $message);

    }

    public function arrayRemoveProvider()
    {
        return [
            [
                "type" => "list",
                "value" => "bobble",
                "expected" => new OArray(["apple", "cobble", "dobble"]),
                "message" => "Basic list",
            ],
            [
                "type" => "dict",
                "value" => "bobble",
                "expected" => new OArray(["a" => "apple", "c" => "cobble", "d" => "dobble"]),
                "message" => "Basic dict",
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
        $capitalize = function ($word, $key) {
            $this->arrDict[$key] = strtoupper($word);
        };

        $this->arrDict->walk($capitalize);


        $this->assertEquals("APPLE", $this->arrDict["a"]);
    }
}
