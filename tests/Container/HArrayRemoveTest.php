<?php
namespace Haystack\Tests\Container;

use Haystack\HArray;

class HArrayRemoveTest extends \PHPUnit_Framework_TestCase
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
            "List: Basic list" => ["list", "bobble", new HArray(["apple", "cobble", "dobble"])],
            "List: Basic list - item not found" => ["list", "zobble", new HArray(["apple", "bobble", "cobble", "dobble"])],
            "Basic dict" => ["dict", "bobble", new HArray(["a" => "apple", "c" => "cobble", "d" => "dobble"])],
            "Basic dict - item not found" => ["dict", "zobble", new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble"])],
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

        $this->arrDict->remove($item);
    }

    public function badRemoveProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime cannot be contained within an HArray"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList cannot be contained within an HArray"],
        ];
    }

}
