<?php
namespace Haystack\Tests\Container;

use Haystack\HString;

class HStringAppendTest extends \PHPUnit_Framework_TestCase
{
    /** @var HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString("foobar");
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
            "Append an HString" => ["babyString" => new HString('baz'), "expected" => "foobarbaz"],
            "Append an integer" => ["babyString" => 5, "expected" => "foobar5"],
            "Append a double" => ["babyString" => 5.1, "expected" => "foobar5.1"],
        ];
    }

    public function testNonScalarTypeCannotBeAddedToFoobar()
    {
        $this->setExpectedException("InvalidArgumentException", "Cannot concatenate an HString with a DateTime");

        $this->aString->append(new \DateTime());
    }
}
