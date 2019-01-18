<?php
namespace Haystack\Tests\Functional;

use Haystack\HArray;
use PHPUnit\Framework\TestCase;

class HArrayWalkTest extends TestCase
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
