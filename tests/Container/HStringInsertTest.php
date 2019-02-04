<?php
declare(strict_types=1);

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
    public function testTypesOfStringInsert(HString $target, $babyString, ?int $location, HString $expected): void
    {
        $babyString = $babyString instanceof HString ? (string) $babyString : $babyString;

        $newString = $target->insert((string) new HString($babyString), $location);

        $this->assertEquals($expected, $newString);
    }

    public function stringInsertProvider(): array
    {
        $aString = new HString('foobar');
        $utf8String = new HString('ɹɐqooɟ');

        return [
            'ASCII String: insert at position 1' => [$aString, 'baz', 1, new HString('fbazoobar')],
            'ASCII String: insert at position -1' => [$aString, 'baz', -1, new HString('foobabazr')],
            'ASCII String: insert at end' => [$aString, 'baz', null, new HString('foobarbaz')],
            'UTF-8 String: insert at position 1' => [$utf8String, 'baz', 1, new HString('ɹbazɐqooɟ')],
            'UTF-8 String: insert at position -1' => [$utf8String, 'baz', -1, new HString('ɹɐqoobazɟ')],
            'UTF-8 String: insert at end' => [$utf8String, 'baz', null, new HString('ɹɐqooɟbaz')],
        ];
    }

    /**
     * @dataProvider badInsertProvider
     *
     * @param mixed $value
     * @param mixed |null $key
     * @param string $exceptionMsg
     * @throws \InvalidArgumentException
     */
    public function testBadInsert($value, $key, string $exceptionMsg): void
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
