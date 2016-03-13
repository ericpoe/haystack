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

    /**
     * @dataProvider stringBadLocateProvider()
     *
     * @param        $checkString
     * @param string $message
     */
    public function testCannotLocateTypesOfStringInFoober($checkString, $message)
    {
        $this->expectException("Haystack\\Container\\ElementNotFoundException");
        $this->expectExceptionMessage($message);

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
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $this->aString->locate($item);
    }

    public function badLocateTypesOfStringInFoobarProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime is neither a scalar value nor an HString"],
            "SplDoublyLinkedList" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList is neither a scalar value nor an HString"],
        ];
    }
}
