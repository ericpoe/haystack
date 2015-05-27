<?php
namespace OPHP\Test;

use OPHP\OArray;
use OPHP\OString;

class OArrayTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \OPHP\OArray */
    private $arrList;
    /** @var  \OPHP\OArray */
    private $arrDict;

    protected function setUp()
    {
        $this->arrList = new OArray(array("apple", "bobble", "cobble", "dobble"));
        $this->arrDict = new OArray(array("a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble"));
    }

    /**
     * @dataProvider arrayContainsProvider
     *
     * @param $type
     * @param $checkThing
     * @param $expected
     */
    public function testStringTypeInOArray($type, $checkThing, $expected)
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
            ["type" => "list", "checkThing" => "apple", "expected" => true],
            ["type" => "list", "checkThing" => "cobble", "expected" => true],
            ["type" => "list", "checkThing" => "fobble", "expected" => false],
            ["type" => "list", "checkThing" => 3, "expected" => false],
            ["type" => "dict", "checkThing" => "apple", "expected" => true],
            ["type" => "dict", "checkThing" => "cobble", "expected" => true],
            ["type" => "dict", "checkThing" => "fobble", "expected" => false],
            ["type" => "dict", "checkThing" => 3, "expected" => false],
        ];
    }


    /**
     * @dataProvider arrayLocateProvider
     *
     * @param $checkThing
     * @param $expected
     */
    public function testLocateStringTypeInOArray($checkThing, $expected)
    {
        $var = $this->arrList->locate($checkThing);
        $this->assertEquals($expected, $var);
    }

    public function arrayLocateProvider()
    {
        return [
            ["checkThing" => "apple", "expected" => 0],
            ["checkThing" => "fobble", "expected" => -1],
            ["checkThing" => new OString("apple"), "expected" => 0],
            ["checkThing" => new OString("fobble"), "expected" => -1],
        ];
    }

    /**
     * @dataProvider appendProvider
     *
     * @param $type
     * @param $newThing
     * @param $expected
     * @throws ErrorException
     */
    public function testAppendStringInArray($type, $newThing, $expected)
    {
        if ("list" === $type) {
            $newArray = $this->arrList->append($newThing);
        } else {
            $newArray = $this->arrDict->append($newThing);
        }

        $this->assertEquals($newArray, $expected);
    }

    public function appendProvider()
    {
        return [
        ["type" => "list", "newThing" => "ebble", "expected" => new OArray(["apple", "bobble", "cobble",
            "dobble", "ebble"])],
        ["type" => "dict", "newThing" => ["e" => "ebble"], "expected" => new OArray(["a" => "apple", "b" =>
            "bobble", "c" => "cobble", "d" => "dobble", ["e" => "ebble"]])],
        ];
    }
}
