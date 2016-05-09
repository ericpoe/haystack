<?php
namespace Haystack\Tests\Container;

use Haystack\HString;

class HStringSliceTest extends \PHPUnit_Framework_TestCase
{
    /** @var HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString("foobar");
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
            "HString" => [new HString("foob")],
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
            "HString" => [new HString("obar")],
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
            "HString: Negative finish, middle" => [2, -2, new HString("ob")],
            "HString: Negative start & finish, middle" => [-4, -2, new HString("ob")],
            "HString: normal middle" => [2, 2, new HString("ob")],
            "HString: null finish" => [2, null, new HString("obar")],
            "HString: overflow finish" => [2, 2000, new HString("obar")],
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
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);

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
