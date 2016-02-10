<?php
namespace OPHP\Tests;

use OPHP\OArray;
use OPHP\OString;

class OArrayFilterTest extends \PHPUnit_Framework_TestCase
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

    public function testArrayDefaultFilter()
    {
        $this->arrList = $this->arrList->insert(0, 2);
        $this->assertEquals(new OArray(["apple", "bobble", "cobble", "dobble"]), $this->arrList->filter(), "List - Default Filter");
        $this->assertEquals(new OArray(["a" => "apple", "b" => "bobble", "c" => "cobble", "d" => "dobble"]), $this->arrDict->filter(), "Dict - Default Filter");

    }

    public function testArrayWithValueFilter()
    {
        $vowel = function ($word) {
            $vowels = new OString("aeiou");

            return $vowels->contains($word[0]);
        };

        $this->assertEquals(new OArray(["apple"]), $this->arrList->filter($vowel), "List - Value Filter");
        $this->assertEquals(new OArray(["a" => "apple"]), $this->arrDict->filter($vowel), "Dict - Value Filter");
    }

    public function testArrayWithKeyFilter()
    {
        $vowelKey = function ($key) {
            $vowels = new OString("aeoiu");

            return $vowels->contains($key);
        };

        $flag = OArray::USE_KEY;
        $arr = new OArray(["a" => "bobble", "b" => "apple", "c" => "cobble"]);
        $this->assertEquals(new OArray(["a" => "bobble"]), $arr->filter($vowelKey, $flag), "Dict - Key Filter");
    }

    public function testArrayWithArrayAndKeyFilter()
    {
        $vowel_both = function ($value, $key) {
            $vowels = new OString("aeiou");

            if ($vowels->contains($value[0])) {
                return true;
            }

            return $vowels->contains($key);
        };

        $flag = OArray::USE_BOTH;
        $arr = new OArray(["a" => "bobble", "b" => "apple", "c" => "cobble"]);
        $this->assertEquals(new OArray(["b" => "apple", "a" => "bobble"]), $arr->filter($vowel_both, $flag), "Dict - Value & Key Filter");
    }

    public function testBadArrayFilterFlag()
    {
        $vowel = function ($word) {
            $vowels = new OString("aeiou");

            return $vowels->contains($word[0]);
        };

        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage("Invalid flag name");

        $this->arrList->filter($vowel, "boooth");
    }
}
