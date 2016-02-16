<?php
namespace OPHP\Tests\Converter;

use OPHP\OArray;
use OPHP\OString;

class ArrayToStringTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider arrayToOstringProvider
     *
     * @param OArray $arr
     * @param        $expected
     */
    public function testArrayToOString(OArray $arr, $expected)
    {
        $this->assertEquals($expected, $arr->toOString());
    }

    public function arrayToOstringProvider()
    {
        return [
            "Empty Array" => [new OArray(), new OString()],
            "1-item list" => [new OArray(["apple"]), new OString("apple")],
            "1-item dict" => [new OArray(["a" => "apple"]), new OString("apple")],
            "2-item list" => [new OArray(["apple", "banana"]), new OString("applebanana")],
            "2-item dict" => [new OArray(["a" => "apple", "b" => "banana"]), new OString("applebanana")],
        ];
    }

    /**
     * @dataProvider arrayToOstringWithGlueProvider
     *
     * @param OArray $arr
     * @param        $glue
     * @param        $expected
     */
    public function testArrayToOStringWithGlue(OArray $arr, $glue, $expected)
    {
        $this->assertEquals($expected, $arr->toOString($glue));
    }

    public function arrayToOstringWithGlueProvider()
    {
        return [
            "Empty Array, null glue" => [new OArray(), null, new OString()],
            "1-item list, null glue" => [new OArray(["apple"]), null, new OString("apple")],
            "1-item dict, null glue" => [new OArray(["a" => "apple"]), null, new OString("apple")],
            "2-item list, null glue" => [new OArray(["apple", "banana"]), null, new OString("applebanana")],
            "2-item dict, null glue" => [new OArray(["a" => "apple", "b" => "banana"]), null, new OString("applebanana")],
            "1-item list, space glue" => [new OArray(["apple"]), " ", new OString("apple")],
            "1-item dict, space glue" => [new OArray(["a" => "apple"]), " ", new OString("apple")],
            "2-item list, space glue" => [new OArray(["apple", "banana"]), " ", new OString("apple banana")],
            "2-item dict, space glue" => [new OArray(["a" => "apple", "b" => "banana"]), " ", new OString("apple banana")],
            "1-item list, OString glue" => [new OArray(["apple"]), new OString(" "), new OString("apple")],
            "1-item dict, OString glue" => [new OArray(["a" => "apple"]), new OString(" "), new OString("apple")],
            "2-item list, OString glue" => [new OArray(["apple", "banana"]), new OString(" "), new OString("apple banana")],
            "2-item dict, OString glue" => [new OArray(["a" => "apple", "b" => "banana"]), new OString(" "), new OString("apple banana")],
        ];
    }

    public function testBadGlueInToOString()
    {
        $arr = new OArray(["apple", "banana"]);

        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage("glue must be a string");

        $arr->toOString(3);
    }
}
