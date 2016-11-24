<?php
namespace Haystack\Tests\Container;

use Haystack\HString;

class HStringContainsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider stringContainsProvider
     *
     * @param string|HString $target
     * @param string|HString $checkString
     * @param bool $expectedBool
     */
    public function testTypesOfStringInFoobar($target, $checkString, $expectedBool)
    {
        $var = $target->contains($checkString);
        $expectedBool ? $this->assertTrue($var) : $this->assertFalse($var);
    }

    public function stringContainsProvider()
    {
        $aString = new HString("foobar");
        $utf8String = new HString("ɹɐqooɟ");
        return [
            "ASCII: String known-present" => [$aString, "oob", true],
            "ASCII: String known-missing" => [$aString, "baz", false],
            "ASCII: String letter known-present" => [$aString, "b", true],
            "ASCII: String letter known-missing" => [$aString, "z", false],
            "ASCII: HString known-present" => [$aString, new HString('oob'), true],
            "ASCII: HString letter known-present" => [$aString, new HString('b'), true],
            "ASCII: HString known-missing" => [$aString, new HString('baz'), false],
            "ASCII: HString letter known-missing" => [$aString, new HString('z'), false],
            "ASCII: Integer known-missing" => [$aString, 42, false],
            "UTF-8: String known-present" => [$utf8String, "ɐqo", true],
            "UTF-8: String known-missing" => [$utf8String, "zɐq", false],
            "UTF-8: String letter known-present" => [$utf8String, "q", true],
            "UTF-8: String letter known-missing" => [$utf8String, "z", false],
            "UTF-8: HString known-present" => [$utf8String, new HString('ɐqo'), true],
            "UTF-8: HString letter known-present" => [$utf8String, new HString('q'), true],
            "UTF-8: HString known-missing" => [$utf8String, new HString('zɐq'), false],
            "UTF-8: HString letter known-missing" => [$utf8String, new HString('z'), false],
            "UTF-8: Integer known-missing" => [$utf8String, 42, false],
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

        (new HString("foobar"))->contains($item);
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
