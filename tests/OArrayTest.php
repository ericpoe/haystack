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
     * @param $type
     * @param $checkThing
     * @param $expected
     */
    public function testLocateStringTypeInOArray($type, $checkThing, $expected)
    {
        if ("list" === $type) {
            $var = $this->arrList->locate($checkThing);
        } else {
            $var = $this->arrDict->locate($checkThing);
        }

        $this->assertEquals($expected, $var);
    }

    public function arrayLocateProvider()
    {
        return [
            ["type" => "list", "checkThing" => "apple", "expected" => 0],
            ["type" => "list", "checkThing" => "fobble", "expected" => -1],
            ["type" => "list", "checkThing" => new OString("apple"), "expected" => 0],
            ["type" => "list", "checkThing" => new OString("fobble"), "expected" => -1],
            ["type" => "dict", "checkThing" => "apple", "expected" => 'a'],
            ["type" => "dict", "checkThing" => "fobble", "expected" => -1],
            ["type" => "dict", "checkThing" => new OString("apple"), "expected" => 'a'],
            ["type" => "dict", "checkThing" => new OString("fobble"), "expected" => -1],
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
                "babyArray" => 5,
                "key" => null,
                "expected" => new OArray(["apple", "bobble","cobble", "dobble", 5])
            ],
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
                "babyArray" => 5,
                "key" => null,
                "expected" => new OArray(["a" => "apple", "b" => "bobble", "c"=> "cobble", "d" => "dobble", 0 => 5])
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

    public function testArrayFilter()
    {
        $vowel = function ($word) {
            $vowels = new OString("aeiou");
            return $vowels->contains($word[0]);
        };

        $vowel_key = function ($key) {
            $vowels = new OString("aeoiu");

            foreach ($vowels as $letter) {
                if ($key === $letter) {
                    return true;
                }
            } return false;
        };

        $vowel_both = function ($value, $key) {
            $vowels = new OString("aeiou");

            if ($vowels->contains($value[0])) {
                return true;
            } else {
                foreach ($vowels as $letter) {
                    if ($key === $letter) {
                        return true;
                    }
                }
            } return false;
        };

        $this->assertEquals(new OArray(["apple"]), $this->arrList->filter($vowel));
        $this->assertEquals(new OArray(["a" => "apple"]), $this->arrDict->filter($vowel));

        $arr = new OArray([0, 1, 2, 3]);
        $this->assertEquals(new OArray([1 => 1, 2 => 2, 3 => 3]), $arr->filter());

        $flag = OArray::USE_KEY;
        $arr = new OArray(["a" => "bobble", "b" => "apple", "c" => "cobble"]);
        $this->assertEquals(new OArray(["a" => "bobble"]), $arr->filter($vowel_key, $flag));

        $flag = OArray::USE_BOTH;
        $arr = new OArray(["a" => "bobble", "b" => "apple", "c" => "cobble"]);
        $this->assertEquals(new OArray(["b" => "apple", "a" => "bobble"]), $arr->filter($vowel_both, $flag));
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
}
