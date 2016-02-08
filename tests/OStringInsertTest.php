<?php
namespace OPHP\Tests;

use OPHP\OString;

class OStringInsertTest extends \PHPUnit_Framework_TestCase
{
    /** @var OString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new OString("foobar");
    }

    /**
     * @dataProvider stringInsertProvider()
     *
     * @param $babyString
     * @param $location
     * @param $expected
     */
    public function testTypesOfStringInsert($babyString, $location, $expected)
    {
        $newString = $this->aString->insert($babyString, $location);

        $this->assertEquals(sprintf("%s", $expected), sprintf("%s", $newString));
    }

    public function stringInsertProvider()
    {
        return [
            "String: insert at position 1" => ["baz", 1, "fbazoobar"],
            "String: insert at position -1" => ["baz", -1, "foobabazr"],
            "String: insert at end" => ["baz", null, "foobarbaz"],
            "String: insert Integer" => [1, 3, "foo1bar"],
            "String: insert Double" => [1.1, 3, "foo1.1bar"],
            "OString: insert at position 1" => [new OString("baz"), 1, "fbazoobar"],
            "OString: insert at position -1" => [new OString("baz"), -1, "foobabazr"],
            "OString: insert at end" => [new OString("baz"), null, "foobarbaz"],
            "OString: insert Integer" => [new OString(1), 3, "foo1bar"],
            "OString: insert Double" => [new OString(1.1), 3, "foo1.1bar"],
        ];
    }

    /**
     * @dataProvider badInsertProvider
     *
     * @param $value
     * @param $key
     * @param $exceptionMsg
     * @throws \InvalidArgumentException
     */
    public function testBadInsert($value, $key, $exceptionMsg)
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $this->aString->insert($value, $key);
    }

    public function badInsertProvider()
    {
        return [
            "Insert DateTime at end" => [new \DateTime(), null, "Cannot insert DateTime into an OString"],
            "Insert SPL object at end" => [new \SplDoublyLinkedList(), null, "Cannot insert SplDoublyLinkedList into an OString"],
            "Insert Array at end" => [['a' => "apple"], null, "Cannot insert array into an OString"],
            "Insert at non-integer key" => ["apple", "a", "Invalid array key"],
        ];
    }

}
