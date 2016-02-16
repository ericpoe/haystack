<?php
namespace Haystack\Tests;

use Haystack\HArray;

class HArrayAppendTest extends \PHPUnit_Framework_TestCase
{
    /** @var  HArray */
    private $arrList;

    /** @var  HArray */
    private $arrDict;

    protected function setUp()
    {
        $this->arrList = new HArray(["apple", "bobble", "cobble", "dobble"]);
        $this->arrDict = new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble"]);
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
            "String to list" => ["list", "ebble", new HArray(["apple", "bobble", "cobble", "dobble", "ebble"])],
            "String array to list" => ["list", ["ebble"], new HArray(["apple", "bobble", "cobble", "dobble", ["ebble"]])],
            "String HArray to list" => ["list", new HArray(["ebble"]), new HArray(["apple", "bobble", "cobble", "dobble", ["ebble"]])],
            "String to dictionary" => ["dict", "ebble", new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", "0" => "ebble"])],
            "String array to dictionary" => ["dict", ["e" => "ebble"], new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", ["e" => "ebble"]])],
            "String HArray to dictionary" => ["dict", new HArray(["e" => "ebble"]), new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble", ["e" => "ebble"]])],
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
            "DateTime" => [new \DateTime(), "DateTime cannot be appended to an HArray"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList cannot be appended to an HArray"],
        ];
    }

}
