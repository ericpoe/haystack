<?php
namespace OPHP\Tests;

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
     * @dataProvider goodArraysProvider
     *
     * @param $type
     * @param $item
     */
    public function testCreateArrayOfThings($type, $item)
    {
        $goodArr = new OArray($item);
        $this->assertArrayHasKey(0, $goodArr->toArray(), $type);
    }

    public function goodArraysProvider()
    {
        return [
            ["type" => "bool - true", "item" => true],
            ["type" => "bool - false", "item" => false],
            ["type" => "integer", "item" => 5],
            ["type" => "integer - 0", "item" => 0],
            ["type" => "array", "item" => [1, 2, 3]],
            ["type" => "ArrayObject", "item" => new \ArrayObject([0, 1, 2])],
            ["type" => "string", "item" => "a"],
            ["type" => "OString", "item" => new OString("a string")],
        ];
    }

    /**
     * @dataProvider badArraysProvider
     * @param $type
     * @param $item
     * @param $exceptionMsg
     */
    public function testCannotCreateArrayOfBadThings($type, $item, $exceptionMsg)
    {
        $this->setExpectedException("ErrorException", $exceptionMsg);
        $badArr = new OArray($item);
        $this->getExpectedException();

    }

    public function badArraysProvider()
    {
        return [
            ["type" => "DateTime", "item" => new \DateTime(), "exceptionMsg" => "DateTime cannot be instantiated as an OArray"],
            ["type" => "SplDoublyLinkedList", "item" => new \SplDoublyLinkedList(), "exceptionMsg" => "SplDoublyLinkedList cannot be instantiated as an OArray" ],
        ];
    }

    /**
     * @dataProvider arrayContainsProvider
     *
     * @param $type
     * @param $checkThing
     * @param $expected
     */
    public function testContainsStringTypeInOArray($type, $checkThing, $expected)
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
     * @dataProvider badArrayContainsProvider
     *
     * @param $type
     * @param $item
     * @param $exceptionMsg
     */
    public function testBadArrayContains($type, $item, $exceptionMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);
        $this->arrList->contains($item);
        $this->getExpectedException();
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
     * @dataProvider badArrayContainsProvider
     *
     * @param $type
     * @param $item
     * @param $exceptionMsg
     */
    public function testLocateBadThingsInOArray($type, $item, $exceptionMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);
        $this->arrList->locate($item);
        $this->getExpectedException();
    }

    public function badArrayContainsProvider()
    {
        return [
            ["type" => "DateTime", "item" => new \DateTime(), "exceptionMsg" => "DateTime cannot be contained within an OArray"],
            ["type" => "SplDoublyLinkedList", "item" => new \SplDoublyLinkedList(), "exceptionMsg" => "SplDoublyLinkedList cannot be contained within an OArray" ],
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
            ["type" => "list", "newThing" => ["ebble"], "expected" => new OArray(["apple", "bobble", "cobble", "dobble", ["ebble"]])],
            ["type" => "dict", "newThing" => ["e" => "ebble"], "expected" => new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", ["e" => "ebble"]])],
        ];
    }

    /**
     * @dataProvider badAppendProvider
     *
     * @param $type
     * @param $item
     * @param $exceptionMsg
     */
    public function testAppendBadThingsToArray($type, $item, $exceptionMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);
        $this->arrList->append($item);
        $this->getExpectedException();
    }

    public function badAppendProvider()
    {
        return [
            ["type" => "DateTime", "item" => new \DateTime(), "exceptionMsg" => "DateTime cannot be appended to an OArray"],
            ["type" => "SplDoublyLinkedList", "item" => new \SplDoublyLinkedList(), "exceptionMsg" => "SplDoublyLinkedList cannot be appended to an OArray" ],
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
     * @dataProvider badArraySliceProvider
     * @param $type
     * @param $start
     * @param $finish
     * @param $expectedMsg
     */
    public function testBadArraySlice($type, $start, $finish, $expectedMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $expectedMsg);
        if ("list" === $type) {
            $subArray = $this->arrList->slice($start, $finish);
        } else {
            $subArray = $this->arrDict->slice($start, $finish);
        }

        $this->getExpectedException();
    }

    public function badArraySliceProvider()
    {
        return [
            ["type" => "list", "start" => "b", "length" => "2", "expectedMsg" => 'Slice parameter 1, $start, must be an integer'],
            ["type" => "dict", "start" => "b", "length" => "2", "expectedMsg" => 'Slice parameter 1, $start, must be an integer'],
            ["type" => "list", "start" => "1", "length" => "b", "expectedMsg" => 'Slice parameter 2, $length, must be null or an integer'],
            ["type" => "dict", "start" => "1", "length" => "b", "expectedMsg" => 'Slice parameter 2, $length, must be null or an integer'],
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
                "babyArray" => new \ArrayObject(["foo"]),
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
                "babyArray" => new \ArrayObject(["b" => "foo"]),
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
     * @dataProvider badArrayInsertProvider
     *
     * @param $type
     * @param $item
     * @param $exceptionMsg
     */
    public function testInsertBadThingsInOArray($type, $item, $exceptionMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);
        $this->arrList->insert($item);
        $this->getExpectedException();
    }

    public function badArrayInsertProvider()
    {
        return [
            ["type" => "DateTime", "item" => new \DateTime(), "exceptionMsg" => "DateTime cannot be contained within an OArray"],
            ["type" => "SplDoublyLinkedList", "item" => new \SplDoublyLinkedList(), "exceptionMsg" => "SplDoublyLinkedList cannot be contained within an OArray" ],
        ];
    }

    /**
     * @dataProvider arrayRemoveProvider
     *
     * @param $type
     * @param $value
     * @param $expected

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
                "type" => "list",
                "value" => "zobble",
                "expected" => new OArray(["apple", "bobble", "cobble", "dobble"]),
                "message" => "Basic list - item not found"
            ],
            [
                "type" => "dict",
                "value" => "bobble",
                "expected" => new OArray(["a" => "apple", "c" => "cobble", "d" => "dobble"]),
                "message" => "Basic dict",
            ],
            [
                "type" => "dict",
                "value" => "zobble",
                "expected" => new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble"]),
                "message" => "Basic dict - item not found",
            ],
        ];
    }

    /**
     * @dataProvider badRemoveProvider
     */
    public function testBadObjectCannotBeRemovedFromArray($type, $item, $exceptionMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);
        $newArray = $this->arrDict->remove($item);
        $this->getExpectedException();
    }

    public function badRemoveProvider()
    {
        return [
            ["type" => "DateTime", "item" => new \DateTime(), "exceptionMsg" => "DateTime cannot be contained within an OArray"],
            ["type" => "SplDoublyLinkedList", "item" => new \SplDoublyLinkedList(), "exceptionMsg" => "SplDoublyLinkedList cannot be contained within an OArray" ],
        ];
    }

    /**
     * @dataProvider badInsertKeyProvider
     */
    public function testObjectCannotBeUsedAsArrayKey($type, $key, $exceptionMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);
        $newArray = $this->arrDict->insert("yobbo", $key);
        $this->getExpectedException();
    }

    public function badInsertKeyProvider()
    {
        return [
            ["type" => "DateTime", "key" => new \DateTime(), "exceptionMsg" => "Invalid array key"],
            ["type" => "SplDoublyLinkedList", "key" => new \SplDoublyLinkedList(), "exceptionMsg" => "Invalid array key" ],
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

    public function testArrayFilter()
    {
        $vowel = function ($word) {
            $vowels = new OString("aeiou");

            return $vowels->contains($word[0]);
        };

        $vowel_key = function ($key) {
            $vowels = new OString("aeoiu");

            return $vowels->contains($key);
        };

        $vowel_both = function ($value, $key) {
            $vowels = new OString("aeiou");

            if ($vowels->contains($value[0])) {
                return true;
            }

            return $vowels->contains($key);
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

    public function testBadArrayFilterFlag()
    {
        $vowel = function ($word) {
            $vowels = new OString("aeiou");
            return $vowels->contains($word[0]);
        };

        $this->setExpectedException("InvalidArgumentException", "Invalid flag name");
        $this->arrList->filter($vowel, "boooth");
        $this->getExpectedException();
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
     * @param              $expected
     * @param \OPHP\OArray $testArr
     */
    public function testArraySum($expected, OArray $testArr)
    {
        $this->assertEquals($expected, $testArr->sum());
    }

    public function arraySumProvider()
    {
        return [
            ['expected' => 0, 'testArr' => new OArray(["apple", "bobble", "cobble"])],
            ['expected' => 5, 'testArr' => new OArray(["apple", "bobble", "cobble", 5])],
            ['expected' => 0, 'testArr' => new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble"])],
            ['expected' => 5, 'testArr' => new OArray(["a" => "apple", "b" => "bobble", "c" => "5"])],
            ['expected' => 55, 'testArr' => new OArray([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])],
            ['expected' => 55, 'testArr' => new OArray(["1", 2, "3", 4, "5", 6, "7", 8, "9", 10])],
            ['expected' => 55, 'testArr' => new OArray(["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5, "f" => 6, "g" => 7, "h" => 8, "i" => 9, "j" => 10])],
        ];
    }

    /**
     * @dataProvider arrayProductProvider()
     *
     * @param              $expected
     * @param \OPHP\OArray $testArr
     */
    public function testArrayProduct($expected, OArray $testArr)
    {
        $this->assertEquals($expected, $testArr->product());
    }

    public function arrayProductProvider()
    {
        return [
            ['expected' => 0, 'testArr' => new OArray(["apple", "bobble", "cobble", 5])],
            ['expected' => 0, 'testArr' => new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "5"])],
            ['expected' => 3628800, 'testArr' => new OArray([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])],
            ['expected' => 3628800, 'testArr' => new OArray(["1", 2, "3", 4, "5", 6, "7", 8, "9", 10])],
            ['expected' => 3628800, 'testArr' => new OArray(["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5, "f" => 6, "g" => 7, "h" => 8, "i" => 9, "j" => 10])],
        ];
    }
}
