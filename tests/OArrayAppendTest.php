<?php
namespace OPHP\Tests;

use OPHP\OArray;

class OArrayAppendTest extends \PHPUnit_Framework_TestCase
{
    /** @var  OArray */
    private $arrList;

    /** @var  OArray */
    private $arrDict;

    protected function setUp()
    {
        $this->arrList = new OArray(["apple", "bobble", "cobble", "dobble"]);
        $this->arrDict = new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble"]);
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

}
