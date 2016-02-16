<?php
namespace OPHP\Tests\Container;

use OPHP\OString;

class OStringSliceTest extends \PHPUnit_Framework_TestCase
{
    /** @var OString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new OString("foobar");
    }

    /**
     * @dataProvider providerFirstPartOfTypesOfStringUsingSlice
     *
     * @param $expected
     */
    public function testGetFirstPartOfTypesOfStringUsingSlice($expected)
    {

        $this->assertEquals($expected, $this->aString->slice(0, 4));

    }

    public function providerFirstPartOfTypesOfStringUsingSlice()
    {
        return [
            "String" => ["foob"],
            "OString" => [new OString("foob")],
        ];
    }

    /**
     * @dataProvider providerLastPartOfTypesOfStringUsingSlice
     *
     * @param $expected
     */
    public function testGetLastPartOfTypesOfStringUsingSlice($expected)
    {
        $this->assertEquals($expected, $this->aString->slice(-4));
    }

    public function providerLastPartOfTypesOfStringUsingSlice()
    {
        return [
            "String" => ["obar"],
            "OString" => [new OString("obar")],
        ];
    }

    /**
     * @dataProvider middlePartOfStringProvider
     *
     * @param $start
     * @param $finish
     * @param $expected
     */
    public function testGetMiddlePartOfTypesOfStringUsingSlice($start, $finish, $expected)
    {
        $this->assertEquals($expected, $this->aString->slice($start, $finish));
    }

    public function middlePartOfStringProvider()
    {
        return [
            "String: Negative finish, middle" => [2, -2, "ob"],
            "String: Negative start & finish, middle" => [-4, -2, "ob"],
            "String: normal middle" => [2, 2, "ob"],
            "String: null finish" => [2, null, "obar"],
            "String: overflow finish" => [2, 2000, "obar"],
            "OString: Negative finish, middle" => [2, -2, new OString("ob")],
            "OString: Negative start & finish, middle" => [-4, -2, new OString("ob")],
            "OString: normal middle" => [2, 2, new OString("ob")],
            "OString: null finish" => [2, null, new OString("obar")],
            "OString: overflow finish" => [2, 2000, new OString("obar")],
        ];
    }

    /**
     * @dataProvider badSlicingProvider()
     *
     * @param $start
     * @param $length
     * @param $exceptionMsg
     */
    public function testBadSlicing($start, $length, $exceptionMsg)
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $this->aString->slice($start, $length);
    }

    public function badSlicingProvider()
    {
        return [
            "No start or length of slice" => [null, null, "Slice parameter 1, \$start, must be an integer"],
            "Non-integer start of slice" => ["cat", 4, "Slice parameter 1, \$start, must be an integer"],
            "Non-integer length of slice" => ["1", "dog", "Slice parameter 2, \$length, must be null or an integer"],
        ];
    }
}
