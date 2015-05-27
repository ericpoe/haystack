<?php
namespace OPHP\Test;

use OPHP\OString;

class OStringTest extends \PHPUnit_Framework_TestCase
{
    /** @var \OPHP\Ostring */
    protected $aString;

    protected function setUp()
    {
        $this->aString= new OString("foobar");
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
            ["checkString" => "oob", "expected" => true],
            ["checkString" => "baz", "expected" => false],
            ["checkString" => new OString('oob'), "expected" => true],
            ["checkString" => new OString('baz'), "expected" => false],
            ["checkString" => 42, "expected" => false],

        ];
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
            ["checkString" => "oob", "expected" => 1],
            ["checkString" => "baz", "expected" => -1],
            ["checkString" => 42, "expected" => -1],
            ["checkString" => new OString('oob'), "expected" => 1],
            ["checkString" => new OString('baz'), "expected" => -1],
            ["checkString" => new OString(42), "expected" => -1],

        ];
    }

    /**
     * @dataProvider stringAppendProvider()
     *
     * @param $babyString
     * @param $expected
     */
    public function testTypesOfStringAppendToFoobar($babyString, $expected)
    {
        $newString = $this->aString->append($babyString);

        $this->assertEquals(sprintf("%s", $expected), sprintf("%s", $newString));
    }

    public function stringAppendProvider()
    {
        return [
            ["babyString" => "baz", "expected" => "foobarbaz"],
            ["babyString" => new OString('baz'), "expected" => "foobarbaz"],
        ];
    }

    /**
     * @expectedException \ErrorException
     */
    public function testDateTimeCannotBeAddedToFoobar()
    {
        $newString = $this->aString->append(new \DateTime());
    }
}
