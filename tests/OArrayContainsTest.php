<?php
namespace OPHP\Tests;

use OPHP\OArray;

class OArrayContainsTest extends \PHPUnit_Framework_TestCase
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
            "1st item in list" => ["list", "apple", true],
            "3rd item in list" => ["list", "cobble", true],
            "String not in list" => ["list", "fobble", false],
            "Int not in list" => ["list", 3, false],
            "1st item in dictionary" => ["dict", "apple", true],
            "3rd item in dictionary" => ["dict", "cobble", true],
            "String not in dictionary" => ["dict", "fobble", false],
            "Int not in dictionary" => ["dict", 3, false],
        ];
    }

    /**
     * @dataProvider badArrayContainsProvider
     *
     * @param $item
     * @param $exceptionMsg
     */
    public function testBadArrayContains($item, $exceptionMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);
        $this->arrList->contains($item);
        $this->getExpectedException();
    }

    public function badArrayContainsProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime cannot be contained within an OArray"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList cannot be contained within an OArray"],
        ];
    }
}
