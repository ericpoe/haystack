<?php
namespace Haystack\Tests\Functional;

use Haystack\HArray;

class HArrayMapTest extends \PHPUnit_Framework_TestCase
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

    public function testArrayMap()
    {
        $capitalizeList = function ($word) {
            return strtoupper($word);
        };

        $newArrList = $this->arrList->map($capitalizeList);
        $this->assertEquals("APPLE", $newArrList[0]);
    }

}
