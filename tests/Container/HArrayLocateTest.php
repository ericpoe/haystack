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
            "1st HString in list" => ["list", new HString("apple"), 0],
            "1st item in dictionary" => ["dict", "apple", 'a'],
            "1st HString in dictionary" => ["dict", new HString("apple"), 'a'],
        ];
    }

    /**
     * @dataProvider elementNotFoundProvider
     *
     * @param $type
     * @param $checkThing
     * @param $message
     */
    public function testElementNotFound($type, $checkThing, $message)
    {
        $this->expectException("Haystack\\Container\\ElementNotFoundException");
        $this->expectExceptionMessage($message);

        if ("list" === $type) {
            $this->arrList->locate($checkThing);
        } else {
            $this->arrDict->locate($checkThing);
        }
    }

    public function elementNotFoundProvider()
    {
        return [
            "String not in list" => ["list", "fobble", "Element could not be found: fobble"],
            "HString not in list" => ["list", new HString("fobble"), "Element could not be found: fobble"],
            "String not in dictionary" => ["dict", "fobble", "Element could not be found: fobble"],
            "HString not in dictionary" => ["dict", new HString("fobble"), "Element could not be found: fobble"],
        ];
    }

    public function testLocateObjectTypeInHArray()
    {
        $timeStamp = new \DateTime();
        $object = new \SplDoublyLinkedList();

        $arrList = $this->arrList
            ->append($timeStamp)
            ->insert($object);

        $this->assertEquals(4, $arrList->locate($timeStamp));
        $this->assertEquals(5, $arrList->locate($object));
    }
}
