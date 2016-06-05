<?php
namespace Haystack\Tests\Container;

use Haystack\HString;

class HStringLocateTest extends \PHPUnit_Framework_TestCase
{
    /** @var HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString("foobar");
    }

    /**
     * @dataProvider stringLocateProvider()
     *
     * @param $checkString
     * @param $expected
     */
    public function testLocateTypesOfStringInFoobar($checkString, $expected)
    {
        $var = $this->aString->locate($checkString);
        $this->assertEquals($expected, $var);
    }

    public function stringLocateProvider()
    {
        return [
            "String known-present" => ["oob", 1],
            "HString known-present" => [new HString('oob'), 1],

        ];
    }

    public function testObjectWithString()
    {
        $date = new \DateTime('now');
        $timeStamp = $date->format('c');
        $timeSentence = new HString(sprintf("I have %s in me.", $timeStamp));
        $this->assertEquals(7, $timeSentence->locate($date->format('c')));

        // This would be a good use of a PHP7 anonymous class
        $obj = new ObjWithToString();
        $sampleString = "I'm a string";
        $objSentence = new HString(sprintf("I have %s in me.", $sampleString));
        $this->assertEquals(7, $objSentence->locate($obj));
    }

    /**
     * @dataProvider stringBadLocateProvider()
     *
     * @param        $checkString
     * @param string $message
     */
    public function testCannotLocateTypesOfStringInFoober($checkString, $message)
    {
        $this->setExpectedException("Haystack\\Container\\ElementNotFoundException", $message);

        $this->aString->locate($checkString);
    }

    public function stringBadLocateProvider()
    {
        return [
            "String known-missing" => ["baz", "Element could not be found: baz"],
            "HString known-missing" => [new HString('baz'), "Element could not be found: baz"],
            "Integer known-missing" => [42, "Element could not be found: 42"],
            "HString integer known-missing" => [new HString(42), "Element could not be found: 42"],
        ];
    }

    /**
     * @dataProvider badLocateTypesOfStringInFoobarProvider
     * @param $item
     * @param $exceptionMsg
     * @throws \InvalidArgumentException
     */
    public function testBadLocateTypesOfStringInFoobar($item, $exceptionMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);

        $this->aString->locate($item);
    }

    public function badLocateTypesOfStringInFoobarProvider()
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
