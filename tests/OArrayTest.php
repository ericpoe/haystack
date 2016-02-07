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
            "OString of Ostring of ... " => [new OString(new OString(new OString(new OString("a string"))))],
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
            "1st item in list" => ["list", "apple", 0],
            "String not in list" => ["list", "fobble", -1],
            "1st OString in list" => ["list", new OString("apple"), 0],
            "OString not in list" => ["list", new OString("fobble"), -1],
            "1st item in dictionary" => ["dict", "apple", 'a'],
            "String not in dictionary" => ["dict", "fobble", -1],
            "1st OString in dictionary" => ["dict", new OString("apple"), 'a'],
            "OString not in dictionary" => ["dict", new OString("fobble"), -1],
        ];
    }

    /**
     * @dataProvider badArrayContainsProvider
     *
     * @param $item
     * @param $exceptionMsg
     */
    public function testLocateBadThingsInOArray($item, $exceptionMsg)
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $this->arrList->locate($item);
    }

    public function badArrayContainsProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime cannot be contained within an OArray"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList cannot be contained within an OArray"],
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
            "String to list" => ["list", "ebble", new OArray(["apple", "bobble", "cobble", "dobble", "ebble"])],
            "String array to list" => ["list", ["ebble"], new OArray(["apple", "bobble", "cobble", "dobble", ["ebble"]])],
            "String OArray to list" => ["list", new OArray(["ebble"]), new OArray(["apple", "bobble", "cobble", "dobble", ["ebble"]])],
            "String to dictionary" => ["dict", "ebble", new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", "0" => "ebble"])],
            "String array to dictionary" => ["dict", ["e" => "ebble"], new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", ["e" => "ebble"]])],
            "String OArray to dictionary" => ["dict", new OArray(["e" => "ebble"]), new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", ["e" => "ebble"]])],
        ];
    }

    /**
     * @dataProvider badAppendProvider
     *
     * @param $item
     * @param $exceptionMsg
     */
    public function testAppendBadThingsToArray($item, $exceptionMsg)
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $this->arrList->append($item);
    }

    public function badAppendProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime cannot be appended to an OArray"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList cannot be appended to an OArray"],
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
            "First two items of list" => ["list", new OArray(["apple", "bobble"])],
            "First two items of dictionary" => ["dict", new OArray(["a" => "apple", "b" => "bobble"])],
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
            "Last two items of list" => ["list", new OArray(["cobble", "dobble"])],
            "Last two items of dictionary" => ["dict", new OArray(["c" => "cobble", "d" => "dobble"])],
        ];
    }

    /**
     * @dataProvider middlePartOfArraySliceProvider
     *
     * @param $type
     * @param $start
     * @param $length
     * @param $expected
     */
    public function testGetMiddlePartOfTypesOfArrayUsingSlice($type, $start, $length, $expected)
    {
        if ("list" === $type) {
            $subArray = $this->arrList->slice($start, $length);
        } else {
            $subArray = $this->arrDict->slice($start, $length);
        }

        $this->assertEquals($expected, $subArray);
    }

    public function middlePartOfArraySliceProvider()
    {
        return [
            "List: Start -3, length: -1" => ["list", "-3", "-1", new OArray(["bobble", "cobble"])],
            "List: Start 1, length: -1" => ["list", "1", "-1", new OArray(["bobble", "cobble"])],
            "List: Start 1, length: 2" => ["list", "1", "2", new OArray(["bobble", "cobble"])],
            "List: Start 1, length: null" => ["list", "1", null, new OArray(["bobble", "cobble", "dobble"])],
            "Dictionary: Start -3, length: -1" => ["dict", "-3", "-1", new OArray(["b" => "bobble", "c" => "cobble"])],
            "Dictionary: Start 1, length: -1" => ["dict", "1", "-1", new OArray(["b" => "bobble", "c" => "cobble"])],
            "Dictionary: Start 1, length: 2" => ["dict", "1", "2", new OArray(["b" => "bobble", "c" => "cobble"])],
            "Dictionary: Start 1, length: null" => ["dict", "1", null, new OArray(["b" => "bobble", "c" => "cobble", "d" => "dobble"])],
        ];
    }

    /**
     * @dataProvider badArraySliceProvider
     * @param $type
     * @param $start
     * @param $length
     * @param $exceptionMsg
     */
    public function testBadArraySlice($type, $start, $length, $exceptionMsg)
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        if ("list" === $type) {
            $subArray = $this->arrList->slice($start, $length);
        } else {
            $subArray = $this->arrDict->slice($start, $length);
        }
    }

    public function badArraySliceProvider()
    {
        return [
            "List: non-integer start" => ["list", "b", "2", 'Slice parameter 1, $start, must be an integer'],
            "Dictionary: non-integer start" => ["dict", "b", "2", 'Slice parameter 1, $start, must be an integer'],
            "List: non-integer length" => ["list", "1", "b", 'Slice parameter 2, $length, must be null or an integer'],
            "Dictionary: non-integer length" => ["dict", "1", "b", 'Slice parameter 2, $length, must be null or an integer'],
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

    /**
     * @dataProvider arrayRemoveProvider
     *
     * @param $type
     * @param $value
     * @param $expected
     */
    public function testArrayTypeRemove($type, $value, $expected)
    {
        if ("list" === $type) {
            $newArr = $this->arrList->remove($value);
        } else {
            $newArr = $this->arrDict->remove($value);
        }

        $this->assertEquals($expected, $newArr);

    }

    public function arrayRemoveProvider()
    {
        return [
            "List: Basic list" => ["list", "bobble", new OArray(["apple", "cobble", "dobble"])],
            "List: Basic list - item not found" => ["list", "zobble", new OArray(["apple", "bobble", "cobble", "dobble"])],
            "Basic dict" => ["dict", "bobble", new OArray(["a" => "apple", "c" => "cobble", "d" => "dobble"])],
            "Basic dict - item not found" => ["dict", "zobble", new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble"])],
        ];
    }

    /**
     * @dataProvider badRemoveProvider
     *
     * @param $item
     * @param $exceptionMsg
     */
    public function testBadObjectCannotBeRemovedFromArray($item, $exceptionMsg)
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $newArray = $this->arrDict->remove($item);
    }

    public function badRemoveProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime cannot be contained within an OArray"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList cannot be contained within an OArray"],
        ];
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

        $newArray = $this->arrDict->insert("yobbo", $key);
    }

    public function badInsertKeyProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "Invalid array key"],
            "SPL Object" => [new \SplDoublyLinkedList(), "Invalid array key"],
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
