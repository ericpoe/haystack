<?php
namespace OPHP\Tests;

use OPHP\OArray;
use OPHP\OString;

class ToOArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider stringToOArrayProvider
     *
     * @param OString $string
     * @param         $delim
     * @param         $limit
     * @param         $expected
     */
    public function testStringToOArray(OString $string, $delim, $limit, $expected)
    {
        $this->assertEquals($expected, $string->toOArray($delim, $limit));
    }

    public function stringToOArrayProvider()
    {
        $jabberwocky = "'Twas brillig and the slithy toves";
        $jabberwockyColon = "'Twas:brillig:and:the:slithy:toves";

        return [
            "Empty String" => [new OString(), null, null, new OArray()],
            "String of integers with null delims" => [new OString("1 2 3 4 5"), null, null, new OArray([1, 2, 3, 4, 5])],
            "String of integers with blank string delims" => [new OString("1 2 3 4 5"), "", null, new OArray([1, 2, 3, 4, 5])],
            "String of integers with space delims" => [new OString("1 2 3 4 5"), " ", null, new OArray([1, 2, 3, 4, 5])],
            "String of integers with comma delims" => [new OString("1, 2, 3, 4, 5"), ",", null, new OArray([1, 2, 3, 4, 5])],
            "String of integers with non-existent delims" => [new OString("1, 2, 3, 4, 5"), "foo", null, new OArray(["1, 2, 3, 4, 5"])],
            "String of integers with OString space delims" => [new OString("1 2 3 4 5"), new OString(" "), null, new OArray([1, 2, 3, 4, 5])],
            "String of integers with OString comma delims" => [new OString("1, 2, 3, 4, 5"), new OString(","), null, new OArray([1, 2, 3, 4, 5])],
            "String of words with spaces" => [new OString($jabberwocky), " ", null, new OArray(["'Twas", "brillig", "and", "the", "slithy", "toves"])],
            "String of words with colons" => [new OString($jabberwockyColon), ":", null, new OArray(["'Twas", "brillig", "and", "the", "slithy", "toves"])],
            "String of integers with spaces & limit" => [new OString("1 2 3 4 5"), " ", 3, new OArray([1, 2, "3 4 5"])],
            "String of integers with commas & limit" => [new OString("1, 2, 3, 4, 5"), ", ", 3, new OArray([1, 2, "3, 4, 5"])],
            "String of words with spaces & limit" => [new OString($jabberwocky), " ", 3, new OArray(["'Twas", "brillig", "and the slithy toves"])],
            "String of words with colons & limit" => [new OString($jabberwockyColon), ":", 3, new OArray(["'Twas", "brillig", "and:the:slithy:toves"])],
        ];
    }

    /**
     * @dataProvider badDelimInStringToArrayProvider
     *
     * @param $delim
     * @param $expectedMsg
     */
    public function testBadDelimInStringToArray($delim, $expectedMsg)
    {
        $string = new OString("foobar");

        $this->setExpectedException("InvalidArgumentException", $expectedMsg);
        $string->toOArray($delim);
        $this->getExpectedException();
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
     * @param $expectedMsg
     */
    public function testBadLimitInStringToArray($limit, $expectedMsg)
    {
        $string = new OString("foobar");

        $this->setExpectedException("InvalidArgumentException", $expectedMsg);
        $string->toOArray(" ", $limit);
        $this->getExpectedException();
    }

    public function badLimitInStringToArrayProvider()
    {
        return [
            "DateTime limit" => [new \DateTime(), "limit must be an integer"],
            "Integer String limit" => ["3", "limit must be an integer"],
        ];
    }
}
