<?php
namespace Haystack\Tests\Converter;

use Haystack\HArray;
use Haystack\HString;

class StringToArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider stringToHArrayProvider
     *
     * @param HString $hString
     * @param $delim
     * @param $limit
     * @param HArray $expected
     */
    public function testStringToHArray(HString $hString, $delim, $limit, HArray $expected)
    {
        $this->assertEquals($expected, $hString->toHArray($delim, $limit));
    }

    public function stringToHArrayProvider()
    {
        $jabberwocky = "'Twas brillig and the slithy toves";
        $jabberwockyColon = "'Twas:brillig:and:the:slithy:toves";

        return [
            "Empty String" => [new HString(), null, null, new HArray()],
            "String of integers with null delims" => [new HString("1 2 3 4 5"), null, null, new HArray([1, 2, 3, 4, 5])],
            "String of integers with blank string delims" => [new HString("1 2 3 4 5"), "", null, new HArray([1, 2, 3, 4, 5])],
            "String of integers with space delims" => [new HString("1 2 3 4 5"), " ", null, new HArray([1, 2, 3, 4, 5])],
            "String of integers with comma delims" => [new HString("1, 2, 3, 4, 5"), ",", null, new HArray([1, 2, 3, 4, 5])],
            "String of integers with non-existent delims" => [new HString("1, 2, 3, 4, 5"), "foo", null, new HArray(["1, 2, 3, 4, 5"])],
            "String of integers with HString space delims" => [new HString("1 2 3 4 5"), new HString(" "), null, new HArray([1, 2, 3, 4, 5])],
            "String of integers with HString comma delims" => [new HString("1, 2, 3, 4, 5"), new HString(","), null, new HArray([1, 2, 3, 4, 5])],
            "String of words with spaces" => [new HString($jabberwocky), " ", null, new HArray(["'Twas", "brillig", "and", "the", "slithy", "toves"])],
            "String of words with colons" => [new HString($jabberwockyColon), ":", null, new HArray(["'Twas", "brillig", "and", "the", "slithy", "toves"])],
            "String of integers with spaces & limit" => [new HString("1 2 3 4 5"), " ", 3, new HArray([1, 2, "3 4 5"])],
            "String of integers with commas & limit" => [new HString("1, 2, 3, 4, 5"), ", ", 3, new HArray([1, 2, "3, 4, 5"])],
            "String of words with spaces & limit" => [new HString($jabberwocky), " ", 3, new HArray(["'Twas", "brillig", "and the slithy toves"])],
            "String of words with colons & limit" => [new HString($jabberwockyColon), ":", 3, new HArray(["'Twas", "brillig", "and:the:slithy:toves"])],
        ];
    }

    /**
     * @dataProvider badDelimInStringToArrayProvider
     *
     * @param $delim
     * @param $exceptionMsg
     */
    public function testBadDelimInStringToArray($delim, $exceptionMsg)
    {
        $hSTring = new HString("foobar");

        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $hSTring->toHArray($delim);
    }

    public function badDelimInStringToArrayProvider()
    {
        return [
            "DateTime delim" => [new \DateTime(), "delimiter must be a string"],
        ];
    }

    /**
     * @dataProvider badLimitInStringToArrayProvider
     * @param $limit
     * @param $exceptionMsg
     */
    public function testBadLimitInStringToArray($limit, $exceptionMsg)
    {
        $hString = new HString("foobar");

        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $hString->toHArray(" ", $limit);
    }

    public function badLimitInStringToArrayProvider()
    {
        return [
            "DateTime limit" => [new \DateTime(), "limit must be an integer"],
            "Integer String limit" => ["3", "limit must be an integer"],
        ];
    }
}
