<?php
namespace Haystack\Tests\Container;

use Haystack\HString;

class HStringInsertTest extends \PHPUnit_Framework_TestCase
{
    /** @var HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString("foobar");
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
            "HString: insert at position 1" => [new HString("baz"), 1, "fbazoobar"],
            "HString: insert at position -1" => [new HString("baz"), -1, "foobabazr"],
            "HString: insert at end" => [new HString("baz"), null, "foobarbaz"],
            "HString: insert Integer" => [new HString(1), 3, "foo1bar"],
            "HString: insert Double" => [new HString(1.1), 3, "foo1.1bar"],
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
            "Insert DateTime at end" => [new \DateTime(), null, "Cannot insert DateTime into an HString"],
            "Insert SPL object at end" => [new \SplDoublyLinkedList(), null, "Cannot insert SplDoublyLinkedList into an HString"],
            "Insert Array at end" => [['a' => "apple"], null, "Cannot insert array into an HString"],
            "Insert at non-integer key" => ["apple", "a", "Invalid array key"],
        ];
    }

}
