<?php
namespace OPHP\Tests\Container;

use OPHP\OArray;
use OPHP\OString;

class OArrayLocateTest extends \PHPUnit_Framework_TestCase
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
}
