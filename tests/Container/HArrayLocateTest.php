<?php
namespace Haystack\Tests\Container;

use Haystack\HArray;
use Haystack\HString;

class HArrayLocateTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @dataProvider arrayLocateProvider
     *
     * @param $type
     * @param $checkThing
     * @param $expected
     */
    public function testLocateStringTypeInHArray($type, $checkThing, $expected)
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
            "1st HString in list" => ["list", new HString("apple"), 0],
            "HString not in list" => ["list", new HString("fobble"), -1],
            "1st item in dictionary" => ["dict", "apple", 'a'],
            "String not in dictionary" => ["dict", "fobble", -1],
            "1st HString in dictionary" => ["dict", new HString("apple"), 'a'],
            "HString not in dictionary" => ["dict", new HString("fobble"), -1],
        ];
    }

    /**
     * @dataProvider badArrayContainsProvider
     *
     * @param $item
     * @param $exceptionMsg
     */
    public function testLocateBadThingsInHArray($item, $exceptionMsg)
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $this->arrList->locate($item);
    }

    public function badArrayContainsProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime cannot be contained within an HArray"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList cannot be contained within an HArray"],
        ];
    }
}
