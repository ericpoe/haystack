<?php
namespace Haystack\Tests\Converter;

use Haystack\HArray;
use Haystack\HString;
use PHPUnit\Framework\TestCase;

class ArrayToStringTest extends TestCase
{
    /**
     * @dataProvider arrayToHstringProvider
     *
     * @param HArray $arr
     * @param string $expected
     */
    public function testArrayToHstring(HArray $arr, $expected)
    {
        $this->assertEquals($expected, $arr->toHString());
    }

    public function arrayToHstringProvider()
    {
        return [
            'Empty Array' => [new HArray(), new HString()],
            '1-item list' => [new HArray(['apple']), new HString('apple')],
            '1-item dict' => [new HArray(['a' => 'apple']), new HString('apple')],
            '2-item list' => [new HArray(['apple', 'banana']), new HString('applebanana')],
            '2-item dict' => [new HArray(['a' => 'apple', 'b' => 'banana']), new HString('applebanana')],
        ];
    }

    /**
     * @dataProvider arrayToHstringWithGlueProvider
     *
     * @param HArray $arr
     * @param string $glue
     * @param string $expected
     */
    public function testArrayToHstringWithGlue(HArray $arr, $glue, $expected)
    {
        $this->assertEquals($expected, $arr->toHString($glue));
    }

    public function arrayToHstringWithGlueProvider()
    {
        return [
            'Empty Array, null glue' => [new HArray(), null, new HString()],
            '1-item list, null glue' => [new HArray(['apple']), null, new HString('apple')],
            '1-item dict, null glue' => [new HArray(['a' => 'apple']), null, new HString('apple')],
            '2-item list, null glue' => [new HArray(['apple', 'banana']), null, new HString('applebanana')],
            '2-item dict, null glue' => [new HArray(['a' => 'apple', 'b' => 'banana']), null, new HString('applebanana')],
            '1-item list, space glue' => [new HArray(['apple']), ' ', new HString('apple')],
            '1-item dict, space glue' => [new HArray(['a' => 'apple']), ' ', new HString('apple')],
            '2-item list, space glue' => [new HArray(['apple', 'banana']), ' ', new HString('apple banana')],
            '2-item dict, space glue' => [new HArray(['a' => 'apple', 'b' => 'banana']), ' ', new HString('apple banana')],
            '1-item list, HString glue' => [new HArray(['apple']), new HString(' '), new HString('apple')],
            '1-item dict, HString glue' => [new HArray(['a' => 'apple']), new HString(' '), new HString('apple')],
            '2-item list, HString glue' => [new HArray(['apple', 'banana']), new HString(' '), new HString('apple banana')],
            '2-item dict, HString glue' => [new HArray(['a' => 'apple', 'b' => 'banana']), new HString(' '), new HString('apple banana')],
        ];
    }
}
