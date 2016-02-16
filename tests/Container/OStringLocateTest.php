<?php
namespace OPHP\Tests\Container;

use OPHP\OString;

class OStringLocateTest extends \PHPUnit_Framework_TestCase
{
    /** @var OString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new OString("foobar");
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
            "String known-missing" => ["baz", -1],
            "OString known-present" => [new OString('oob'), 1],
            "OString known-missing" => [new OString('baz'), -1],
            "Integer known-missing" => [42, -1],
            "OString integer known-missing" => [new OString(42), -1],

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
            "DateTime" => [new \DateTime(), "DateTime is neither a scalar value nor an OString"],
            "SplDoublyLinkedList" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList is neither a scalar value nor an OString"],
        ];
    }
}
