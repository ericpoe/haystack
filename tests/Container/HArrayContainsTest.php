<?php
namespace Haystack\Tests;

use Haystack\HArray;

class HArrayContainsTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Haystack\HArray */
    private $arrList;
    /** @var  \Haystack\HArray */
    private $arrDict;

    protected function setUp()
    {
        $this->arrList = new HArray(["apple", "bobble", "cobble", "dobble"]);
        $this->arrDict = new HArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble"]);
    }

    /**
     * @dataProvider arrayContainsProvider
     *
     * @param $type
     * @param $checkThing
     * @param $expected
     */
    public function testContainsStringTypeInHArray($type, $checkThing, $expected)
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

    public function testContainsObjectTypeInHArray()
    {
        $list = $this->arrList->append(new \SplDoublyLinkedList());

        $this->assertTrue($list->contains(new \SplDoublyLinkedList()), "SplDoublyLinkedList should be in the list");
        $this->assertFalse($list->contains(new \DateTime()), "DateTime should be in the list");
    }
}
