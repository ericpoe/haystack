<?php
namespace OPHP\Tests;

use OPHP\OString;

class OStringContainsTest extends \PHPUnit_Framework_TestCase
{
    /** @var \OPHP\Ostring */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new OString("foobar");
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
            "OString known-present" => [new OString('oob'), true],
            "OString letter known-present" => [new OString('b'), true],
            "OString known-missing" => [new OString('baz'), false],
            "OString letter known-missing" => [new OString('z'), false],
            "Integer known-missing" => [42, false],

        ];
    }

    /**
     * @dataProvider badTypesOfStringInFoobar
     * @param $item
     * @param $message
     * @throws \InvalidArgumentException
     */
    public function testBadTypesOfStringInFoobar($item, $message)
    {
        $this->setExpectedException("InvalidArgumentException", $message);
        $var = $this->aString->contains($item);
    }

    public function badTypesOfStringInFoobar()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime is neither a scalar value nor an OString"],
            "SplDoublyLinkedList" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList is neither a scalar value nor an OString"],
        ];
    }

}
