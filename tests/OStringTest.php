<?php
namespace OPHP\Test;

use OPHP\OArray;
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

    public function testGetFirstPartOfTypesOfStringUsingSlice()
    {
        $substr1 = "foob";
        $substr2 = new OString("foob");

        $this->assertEquals($substr1, $this->aString->slice(0, 4));
        $this->assertEquals($substr2, $this->aString->slice(0, 4));

    }

    public function testGetLastPartOfTypesOfStringUsingSlice()
    {
        $substr1 = "obar";
        $substr2 = new OString("obar");

        $this->assertEquals($substr1, $this->aString->slice(-4));
        $this->assertEquals($substr2, $this->aString->slice(-4));
    }

    public function testGetMiddlePartOfTypesOfStringUsingSlice()
    {
        $substr1 = "ob";
        $substr2 = new OString("ob");

        $this->assertEquals($substr1, $this->aString->slice(-4, -2));
        $this->assertEquals($substr2, $this->aString->slice(-4, -2));
    }

    /**
     * @dataProvider stringInsertProvider()
     *
     * @param $babyString
     * @param $location
     * @param $expected
     */
    public function testTypesOfStringInsert($babyString, $location, $expected)
    {
        $newString = $this->aString->insert($babyString, $location);

        $this->assertEquals(sprintf("%s", $expected), sprintf("%s", $newString));
    }

    public function stringInsertProvider()
    {
        return [
            ["babyString" => "baz", "location" => "1", "expected" => "fbazoobar"],
            ["babyString" => "baz", "location" => null, "expected" => "foobarbaz"],
            ["babyString" => new OString("baz"), "location" => "1", "expected" => "fbazoobar"],
            ["babyString" => new OString("baz"), "location" => null, "expected" => "foobarbaz"],
        ];
    }

    /**
     * @expectedException \ErrorException
     */
    public function testNonStringTypeCannotBeAddedToFoobar()
    {
        $newString = $this->aString->append(new \DateTime());
    }
}
