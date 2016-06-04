<?php
namespace Haystack\Tests\Container;

use Haystack\HString;

class HStringContainsTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Haystack\HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString("foobar");
    }

    /**
     * @dataProvider stringContainsProvider
     *
     * @param $checkString
     * @param $expectedBool
     */
    public function testTypesOfStringInFoobar($checkString, $expectedBool)
    {
        $var = $this->aString->contains($checkString);
        $expectedBool ? $this->assertTrue($var) : $this->assertFalse($var);
    }

    public function stringContainsProvider()
    {
        return [
            "String known-present" => ["oob", true],
            "String known-missing" => ["baz", false],
            "String letter known-present" => ["b", true],
            "String letter known-missing" => ["z", false],
            "HString known-present" => [new HString('oob'), true],
            "HString letter known-present" => [new HString('b'), true],
            "HString known-missing" => [new HString('baz'), false],
            "HString letter known-missing" => [new HString('z'), false],
            "Integer known-missing" => [42, false],
        ];
    }

    public function testObjectWithString()
    {
        $date = new \DateTime('now');
        $timeStamp = $date->format('c');
        $timeSentence = new HString(sprintf("I have %s in me.", $timeStamp));
        $this->assertTrue($timeSentence->contains($date->format('c')));

        // This would be a good use of a PHP7 anonymous class
        $obj = new ObjWithToString();
        $sampleString = "I'm a string";
        $objSentence = new HString(sprintf("I have %s in me.", $sampleString));
        $this->assertTrue($objSentence->contains($obj));
    }

    /**
     * @dataProvider badTypesOfStringInFoobar
     * @param $item
     * @param $exceptionMsg
     * @throws \InvalidArgumentException
     */
    public function testBadTypesOfStringInFoobar($item, $exceptionMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);

        $this->aString->contains($item);
    }

    public function badTypesOfStringInFoobar()
    {
        return [
            "DateTime" => [
                new \DateTime(),
                "DateTime cannot be converted to a string; it cannot be used as a search value within an HString"
            ],
            "SplDoublyLinkedList" => [
                new \SplDoublyLinkedList(),
                "SplDoublyLinkedList cannot be converted to a string; it cannot be used as a search value within an HString"
            ],
        ];
    }
}
