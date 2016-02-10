<?php
namespace OPHP\Tests;

use OPHP\OArray;

class OArrayRemoveTest extends \PHPUnit_Framework_TestCase
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

}
