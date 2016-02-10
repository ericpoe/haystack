<?php
namespace OPHP\Tests;

use OPHP\OArray;

class OArrayWalkTest extends \PHPUnit_Framework_TestCase
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

    public function testArrayWalk()
    {
        $capitalizeDict = function ($word, $key) {
            return $this->arrDict[$key] = strtoupper($word);
        };

        $capitalizeList = function ($word, $key) {
            return $this->arrList[$key] = strtoupper($word);
        };

        $this->arrDict->walk($capitalizeDict);
        $this->assertEquals("APPLE", $this->arrDict["a"]);

        $this->arrList->walk($capitalizeList);
        $this->assertEquals("APPLE", $this->arrList[0]);
    }
}
