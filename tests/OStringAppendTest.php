<?php
namespace OPHP\Tests;

use OPHP\OString;

class OStringAppendTest extends \PHPUnit_Framework_TestCase
{
    /** @var OString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new OString("foobar");
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
            "Append a normal string" => ["babyString" => "baz", "expected" => "foobarbaz"],
            "Append an OString" => ["babyString" => new OString('baz'), "expected" => "foobarbaz"],
            "Append an integer" => ["babyString" => 5, "expected" => "foobar5"],
            "Append a double" => ["babyString" => 5.1, "expected" => "foobar5.1"],
        ];
    }

    public function testNonScalarTypeCannotBeAddedToFoobar()
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage("Cannot concatenate an OString with a DateTime");

        $this->aString->append(new \DateTime());
    }
}
