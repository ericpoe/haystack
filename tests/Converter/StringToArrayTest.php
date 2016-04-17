<?php
namespace Haystack\Tests\Converter;

use Haystack\HArray;
use Haystack\HString;

class StringToArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testHStringToArray()
    {
        $jabberwocky = new HString("'Twas brillig and the slithy toves");
        $expected = [
            "'Twas",
            "brillig",
            "and",
            "the",
            "slithy",
            "toves",
        ];

        $this->assertEquals($expected, $jabberwocky->toArray());
    }

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
            "String of integers with null delims" => [new HString("1 2 3 4 5"), null, null, new HArray([1, " ", 2, " " , 3," ", 4, " ", 5])],
            "String of integers with blank string delims" => [new HString("1 2 3 4 5"), null, null, new HArray([1, " ", 2, " " , 3," ", 4, " ", 5])],
            "String of integers with space delims" => [new HString("1 2 3 4 5"), " ", null, new HArray([1, 2, 3, 4, 5])],
            "String of integers with comma delims" => [new HString("1, 2, 3, 4, 5"), ",", null, new HArray([1, 2, 3, 4, 5])],
            "String of integers with non-existent delims" => [new HString("1, 2, 3, 4, 5"), "foo", null, new HArray(["1, 2, 3, 4, 5"])],
            "String of integers with HString space delims" => [new HString("1 2 3 4 5"), new HString(" "), null, new HArray([1, 2, 3, 4, 5])],
            "String of integers with HString comma delims" => [new HString("1, 2, 3, 4, 5"), new HString(","), null, new HArray([1, 2, 3, 4, 5])],
            "String of words with null delims" => [new HString($jabberwocky), null, null, new HArray(["'", "T", "w", "a", "s", " ", "b", "r", "i", "l", "l", "i", "g", " ", "a", "n", "d", " ", "t", "h", "e", " ", "s", "l", "i", "t", "h", "y", " ", "t", "o", "v", "e", "s"])],
            "String of words with space delims" => [new HString($jabberwocky), " ", null, new HArray(["'Twas", "brillig", "and", "the", "slithy", "toves"])],
            "String of words with colon delims" => [new HString($jabberwockyColon), ":", null, new HArray(["'Twas", "brillig", "and", "the", "slithy", "toves"])],
            "String of integers with space delims & limit" => [new HString("1 2 3 4 5"), " ", 3, new HArray([1, 2, "3 4 5"])],
            "String of integers with comma delims & limit" => [new HString("1, 2, 3, 4, 5"), ", ", 3, new HArray([1, 2, "3, 4, 5"])],
            "String of words with space delims & limit" => [new HString($jabberwocky), " ", 3, new HArray(["'Twas", "brillig", "and the slithy toves"])],
            "String of words with colon delims & limit" => [new HString($jabberwockyColon), ":", 3, new HArray(["'Twas", "brillig", "and:the:slithy:toves"])],
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

        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);

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

        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);

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
