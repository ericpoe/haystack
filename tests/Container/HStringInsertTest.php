<?php
namespace Haystack\Tests\Container;

use Haystack\HString;
use PHPUnit\Framework\TestCase;

class HStringInsertTest extends TestCase
{
    /**
     * @dataProvider stringInsertProvider()
     *
     * @param HString $target
     * @param string|HString $babyString
     * @param integer $location
     * @param HString $expected
     */
    public function testTypesOfStringInsert(HString $target, $babyString, $location, HString $expected)
    {
        $newString = $target->insert($babyString, $location);

        $this->assertEquals(sprintf('%s', $expected), sprintf('%s', $newString));
    }

    public function stringInsertProvider()
    {
        $aString = new HString('foobar');
        $utf8String = new HString('ɹɐqooɟ');

        return [
            'ASCII String: insert at position 1' => [$aString, 'baz', 1, new HString('fbazoobar')],
            'ASCII String: insert at position -1' => [$aString, 'baz', -1, new HString('foobabazr')],
            'ASCII String: insert at end' => [$aString, 'baz', null, new HString('foobarbaz')],
            'ASCII String: insert Integer' => [$aString, 1, 3, new HString('foo1bar')],
            'ASCII String: insert Double' => [$aString, 1.1, 3, new HString('foo1.1bar')],
            'ASCII HString: insert at position 1' => [$aString, new HString('baz'), 1, new HString('fbazoobar')],
            'ASCII HString: insert at position -1' => [$aString, new HString('baz'), -1, new HString('foobabazr')],
            'ASCII HString: insert at end' => [$aString, new HString('baz'), null, new HString('foobarbaz')],
            'ASCII HString: insert Integer' => [$aString, new HString(1), 3, new HString('foo1bar')],
            'ASCII HString: insert Double' => [$aString, new HString(1.1), 3, new HString('foo1.1bar')],
            'UTF-8 String: insert at position 1' => [$utf8String, 'baz', 1, new HString('ɹbazɐqooɟ')],
            'UTF-8 String: insert at position -1' => [$utf8String, 'baz', -1, new HString('ɹɐqoobazɟ')],
            'UTF-8 String: insert at end' => [$utf8String, 'baz', null, new HString('ɹɐqooɟbaz')],
            'UTF-8 String: insert Integer' => [$utf8String, 1, 3, new HString('ɹɐq1ooɟ')],
            'UTF-8 String: insert Double' => [$utf8String, 1.1, 3, new HString('ɹɐq1.1ooɟ')],
            'UTF-8 HString: insert at position 1' => [$utf8String, new HString('baz'), 1, new HString('ɹbazɐqooɟ')],
            'UTF-8 HString: insert at position -1' => [$utf8String, new HString('baz'), -1, new HString('ɹɐqoobazɟ')],
            'UTF-8 HString: insert at end' => [$utf8String, new HString('baz'), null, new HString('ɹɐqooɟbaz')],
            'UTF-8 HString: insert Integer' => [$utf8String, new HString(1), 3, new HString('ɹɐq1ooɟ')],
            'UTF-8 HString: insert Double' => [$utf8String, new HString(1.1), 3, new HString('ɹɐq1.1ooɟ')],
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
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage($exceptionMsg);

        (new HString('foobar'))->insert($value, $key);
    }

    public function badInsertProvider()
    {
        return [
            'Insert DateTime at end' => [new \DateTime(), null, 'Cannot insert DateTime into an HString'],
            'Insert SPL object at end' => [new \SplDoublyLinkedList(), null, 'Cannot insert SplDoublyLinkedList into an HString'],
            'Insert Array at end' => [['a' => 'apple'], null, 'Cannot insert array into an HString'],
            'Insert at non-integer key' => ['apple', 'a', 'Invalid array key'],
        ];
    }
}
