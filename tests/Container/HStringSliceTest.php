<?php
namespace Haystack\Tests\Container;

use Haystack\HString;

class HStringSliceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerFirstPartOfTypesOfStringUsingSlice
     *
     * @param HString $target
     * @param $expected
     */
    public function testGetFirstPartOfTypesOfStringUsingSlice(HString $target, $expected)
    {
        $this->assertEquals($expected, $target->slice(0, 4));
    }

    public function providerFirstPartOfTypesOfStringUsingSlice()
    {
        return [
            "String" => [new HString("foobar"), "foob"],
            "ASCII HString" => [new HString("foobar"), new HString("foob")],
            "UTF-8 HString" => [new HString("ɹɐqooɟ"), new HString("ɹɐqo")],
        ];
    }

    /**
     * @dataProvider providerLastPartOfTypesOfStringUsingSlice
     *
     * @param HString $target
     * @param $expected
     */
    public function testGetLastPartOfTypesOfStringUsingSlice(HString $target, $expected)
    {
        $this->assertEquals($expected, $target->slice(-4));
    }

    public function providerLastPartOfTypesOfStringUsingSlice()
    {
        return [
            "HString" => [new HString("foobar"), new HString("obar")],
            "UTF-8 HString" => [new HString("ɹɐqooɟ"), new HString("qooɟ")],
        ];
    }

    /**
     * @dataProvider middlePartOfStringProvider
     *
     * @param HString $target
     * @param HString $expected
     * @param integer $start
     * @param integer $finish
     */
    public function testGetMiddlePartOfTypesOfStringUsingSlice(HString $target, HString $expected, $start, $finish)
    {
        $this->assertEquals($expected, $target->slice($start, $finish));
    }

    public function middlePartOfStringProvider()
    {
        return [
            "ASCII HString: Negative finish, middle" => [new HString("foobar"), new HString("ob"), 2, -2],
            "ASCII HString: Negative start & finish, middle" => [new HString("foobar"), new HString("ob"), -4, -2],
            "ASCII HString: normal middle" => [new HString("foobar"), new HString("ob"), 2, 2],
            "ASCII HString: null finish" => [new HString("foobar"), new HString("obar"), 2, null],
            "ASCII HString: overflow finish" => [new HString("foobar"), new HString("obar"), 2, 2000],
            "UTF-8 HString: Negative finish, middle" => [new HString("ɹɐqooɟ"), new HString("qo"), 2, -2],
            "UTF-8 HString: Negative start & finish, middle" => [new HString("ɹɐqooɟ"), new HString("qo"), -4, -2],
            "UTF-8 HString: normal middle" => [new HString("ɹɐqooɟ"), new HString("qo"), 2, 2],
            "UTF-8 HString: null finish" => [new HString("ɹɐqooɟ"), new HString("qooɟ"), 2, null],
            "UTF-8 HString: overflow finish" => [new HString("ɹɐqooɟ"), new HString("qooɟ"), 2, 2000],
        ];
    }

    /**
     * @dataProvider badSlicingProvider()
     *
     * @param HString $target
     * @param integer $start
     * @param integer $length
     * @param $exceptionMsg
     */
    public function testBadSlicing(HString $target, $start, $length, $exceptionMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);

        $target->slice($start, $length);
    }

    public function badSlicingProvider()
    {
        return [
            "ASCII HString: No start or length of slice" =>
                [new HString("foobar"), null, null, "Slice parameter 1, \$start, must be an integer"],
            "ASCII HString: Non-integer start of slice" =>
                [new HString("foobar"), "cat", 4, "Slice parameter 1, \$start, must be an integer"],
            "ASCII HString: Non-integer length of slice" =>
                [new HString("foobar"), "1", "dog", "Slice parameter 2, \$length, must be null or an integer"],
            "UTF-8 HString: No start or length of slice" =>
                [new HString("ɹɐqooɟ"), null, null, "Slice parameter 1, \$start, must be an integer"],
            "UTF-8 HString: Non-integer start of slice" =>
                [new HString("ɹɐqooɟ"), "cat", 4, "Slice parameter 1, \$start, must be an integer"],
            "UTF-8 HString: Non-integer length of slice" =>
                [new HString("ɹɐqooɟ"), "1", "dog", "Slice parameter 2, \$length, must be null or an integer"],
        ];
    }
}
