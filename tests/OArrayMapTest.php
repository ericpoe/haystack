<?php
namespace OPHP\Tests;

use OPHP\OArray;

class OArrayMapTest extends \PHPUnit_Framework_TestCase
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

    public function testArrayMap()
    {
        $capitalizeList = function ($word) {
            return strtoupper($word);
        };

        $newArrList = $this->arrList->map($capitalizeList);
        $this->assertEquals("APPLE", $newArrList[0]);
    }

}
