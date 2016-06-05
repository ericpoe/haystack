<?php
namespace Haystack\Tests\Functional;

use Haystack\HArray;
use Haystack\HString;

class HArrayMapTest extends \PHPUnit_Framework_TestCase
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

    public function testArrayMapWithNoExtra()
    {
        $capitalizeList = function ($word) {
            return strtoupper($word);
        };

        $newArrList = $this->arrList->map($capitalizeList);
        $this->assertEquals("APPLE", $newArrList[0]);

        $newArrDict = $this->arrDict->map($capitalizeList);
        $this->assertEquals("APPLE", $newArrDict["a"]);
    }

    /**
     * @dataProvider oneExtraArraysProvider
     *
     * @param HArray   $expected
     * @param HArray   $numArray - variadic array
     */
    public function testArrayMapWithOneExtra(HArray $numArray, HArray $expected)
    {
        $action = function ($word, $numWord) {
            return sprintf("I have %s %s", $numWord, $word);
        };

        $actual = $this->arrList->map($action, $numArray);
        $this->assertEquals($expected, $actual);
    }

    public function oneExtraArraysProvider()
    {
        $numFewer = new HArray(["one", "two", "three"]);
        $numEqual = new HArray(["one", "two", "three", "four"]);
        $numGreater = new HArray(["one", "two", "three", "four", "five"]);

        return [
            "One-Extra array size of one fewer does meet expectations" => [
                $numFewer,
                new HArray([
                    "I have one apple",
                    "I have two bobble",
                    "I have three cobble",
                    "I have  dobble" // extra space due to missing placeholder
                ]),
            ],
            "One-Extra array size of equivalence does not meet expectations" => [
                $numEqual,
                new HArray([
                    "I have one apple",
                    "I have two bobble",
                    "I have three cobble",
                    "I have four dobble"
                ]),
            ],
            "One-Extra array size of one greater does not meet expectations" => [
                $numGreater,
                new HArray([
                    "I have one apple",
                    "I have two bobble",
                    "I have three cobble",
                    "I have four dobble",
                    "I have five " // extra space due to missing placeholder
                ]),
            ],
        ];
    }

    /**
     * @dataProvider twoExtraArraysProvider
     *
     * @param HArray   $expected
     * @param HArray   $numArray - variadic array
     * @param HArray   $adjArray - variadic array
     */
    public function testArrayMapWithTwoExtras(HArray $numArray, HArray $adjArray, HArray $expected)
    {
        $action = function ($noun, $numWord, $adjective) {
            return sprintf("I have %s %s %s", $numWord, $adjective, $noun);
        };

        $actual = $this->arrList->map($action, $numArray, $adjArray);
        $this->assertEquals($expected, $actual);
    }

    public function twoExtraArraysProvider()
    {
        $numFewer = new HArray(["one", "two", "three"]);
        $numEqual = new HArray(["one", "two", "three", "four"]);
        $numGreater = new HArray(["one", "two", "three", "four", "five"]);

        $adjFewer = new HArray(["large", "small"]);
        $adjEqual = new HArray(["large", "small", "ripe", "rotten"]);
        $adjGreater = new HArray(["large", "small", "ripe", "rotten", "red", "green"]);

        return [
            "Two-Extra array size of fewer does not meet expectations" => [
                $numFewer,
                $adjFewer,
                new HArray([
                    "I have one large apple",
                    "I have two small bobble",
                    "I have three  cobble", // extra spaces due to missing placeholder
                    "I have   dobble" // extra spaces due to missing placeholders
                ]),
            ],
            "Two-Extra array size of equivalence does not meet expectations" => [
                $numEqual,
                $adjEqual,
                new HArray([
                    "I have one large apple",
                    "I have two small bobble",
                    "I have three ripe cobble",
                    "I have four rotten dobble"
                ]),
            ],
            "Two-Extra array size of greater does not meet expectations" => [
                $numGreater,
                $adjGreater,
                new HArray([
                    "I have one large apple",
                    "I have two small bobble",
                    "I have three ripe cobble",
                    "I have four rotten dobble",
                    "I have five red ", // extra spaces due to missing placeholder
                    "I have  green " // extra spaces due to missing placeholders
                ]),
            ],
        ];
    }

    public function testArrayMapWithVariadicHString()
    {
        $phrase = new HString("abcd");

        $sentence = function ($word, $letter) {
            return sprintf("%s starts with %s", $word, $letter);
        };

        $oddThing = $this->arrList->map($sentence, $phrase);

        $expected = new HArray([
            "apple starts with a",
            "bobble starts with b",
            "cobble starts with c",
            "dobble starts with d",
        ]);
        $this->assertEquals($expected, $oddThing);
    }

    public function testErrorGetsThrown()
    {
        $badStr = new \stdClass();
        $this->setExpectedException("InvalidArgumentException", "stdClass cannot be mapped");

        $action = function ($letterFromHaystack, $thingFromBadThings) {
            return sprintf("%s:%s", $letterFromHaystack, $thingFromBadThings);
        };

        $badMapping = $this->arrList->map($action, $badStr);
    }
}
