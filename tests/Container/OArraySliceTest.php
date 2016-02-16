<?php
namespace OPHP\Tests\Container;

use OPHP\OArray;

class OArraySliceTest extends \PHPUnit_Framework_TestCase
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
}
