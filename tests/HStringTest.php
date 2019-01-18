<?php
namespace Haystack\Tests;

use Haystack\HString;
use PHPUnit\Framework\TestCase;

class HStringTest extends TestCase
{
    /** @var HString */
    protected $aString;

    /** @var  HString */
    protected $utf8String;

    protected function setUp()
    {
        $this->aString = new HString('foobar');
        $this->utf8String = new HString('ɹɐqooɟ');
    }

    public function testCreateEmptyString()
    {
        $emptyString = new HString();
        $this->assertEmpty($emptyString);
    }

    /**
     * @dataProvider stringOfThingsProvider
     *
     * @param $item
     * @param $expected
     */
    public function testCreateHStringOfThings($item, $expected)
    {
        $this->aString = new HString($item);
        $this->assertEquals($expected, $this->aString);
    }

    public function stringOfThingsProvider()
    {
        $timeStamp = new \DateTime();

        return [
            'Empty String' => [' ', ' '],
            'HString' => [new HString('abc'), 'abc'],
            'HString of HString of HString of...' => [new HString(new HString(new HString(new HString('abc')))), 'abc'],
            'Simple string' => ['abc', 'abc'],
            'UTF-8 string' => ['ɹɐqooɟ', 'ɹɐqooɟ'],
            'integer 1' => [1, '1'],
            'integer 0' => [0, '0'],
            'double 1.1' => [1.1, '1.1'],
            'DateTime formatted timestamp' => [$timeStamp->format('c'), $timeStamp->format('c')],
            'boolean true' => [true, '1'],
            'boolean false' => [false, ''],
            'Blank string' => ['', ''],
            'Null string' => [null, ''],
        ];
    }

    /**
     * @dataProvider createBadHStringProvider
     *
     * @param $item
     * @param $exceptionMsg
     */
    public function testCreateBadHStringOfThings($item, $exceptionMsg)
    {
        $this->expectException('ErrorException');
        $this->expectExceptionMessage($exceptionMsg);

        $this->aString = new HString($item);
    }

    public function createBadHStringProvider()
    {
        return [
            'DateTime' => [new \DateTime(), 'DateTime is not a proper String'],
            'SPL Object' => [new \SplDoublyLinkedList(), 'SplDoublyLinkedList is not a proper String'],
        ];
    }

    public function testSerialize()
    {
        $serialized = $this->aString->serialize();
        $this->assertEquals(serialize($this->aString->toString()), $serialized);

        $serialized = $this->utf8String->serialize();
        $this->assertEquals(serialize($this->utf8String->toString()), $serialized);
    }

    /**
     * @dataProvider unserializeProvider
     *
     * @param string $str
     * @param HString $expected
     */
    public function testUnserialize($str, HString $expected)
    {
        $this->aString->unserialize($str);
        $this->assertEquals($expected, $this->aString);
    }

    public function unserializeProvider()
    {
        return [
            'String' => [serialize(new HString('foobar')), new HString('foobar')],
            'String with spaces' => [serialize('The quick brown fox jumps'), new HString('The quick brown fox jumps')],
            'UTF-8 string' => [serialize('ɹɐqooɟ'), new HString('ɹɐqooɟ')],
            'Null string' => [serialize(null), new HString()],
            'Unserialized null string' => [null, new HString()],
        ];
    }

    /**
     * @dataProvider badUnserializeProvider
     * @param $item
     * @param $exceptionMsg
     */
    public function testBadUnserialize($item, $exceptionMsg)
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage($exceptionMsg);

        $this->aString->unserialize($item);
    }

    public function badUnserializeProvider()
    {
        return [
            'Unserialized HString' => [new HString('The quick brown fox'), "HString cannot unserialize a Haystack\\HString"],
            'DateTime object' => [new \DateTime(), 'HString cannot unserialize a DateTime'],
        ];
    }

    public function testIteratorNext()
    {
        $this->aString->next();
        $this->assertEquals('o', $this->aString->current());

        $this->utf8String->next();
        $this->assertEquals('ɐ', $this->utf8String->current());
    }

    /**
     * @dataProvider iteratorValidProvider
     *
     * @param HString $toIterate
     */
    public function testIteratorValid(HString $toIterate)
    {
        $toIterate->next(); // "o"
        $toIterate->next(); // "o"
        $toIterate->next(); // "b"
        $toIterate->next(); // "a"
        $toIterate->next(); // "r"
        $this->assertTrue($toIterate->valid());
        $toIterate->next(); // uninitialized string offset
        $this->assertFalse($toIterate->valid());
    }

    public function iteratorValidProvider()
    {
        return [
            'ASCII string to iterate' => [new HString('foobar')],
            'UTF-8 string to iterate' => [new HString('ɹɐqooɟ')],
        ];
    }

    /**
     * @dataProvider iteratorRewindProvider
     *
     * @param HString $toRewind
     * @param integer $expected
     */
    public function testIteratorRewind(HString $toRewind, $expected)
    {
        $toRewind->next(); // "o"
        $toRewind->next(); // "o"
        $toRewind->next(); // 'b"

        $toRewind->rewind(); // back to "f"
        $this->assertEquals($expected, $toRewind->current());

        $toRewind->rewind(); // before the first char!
        $this->assertEquals($expected, $toRewind->current());
    }

    public function iteratorRewindProvider()
    {
        return [
            'ASCII string to iterate' => [new HString('foobar'), 'f'],
            'UTF-8 string to iterate' => [new HString('ɹɐqooɟ'), 'ɹ'],
        ];
    }

    /**
     * @dataProvider iteratorKeyProvider
     *
     * @param HString $toIterate
     * @param integer $expected
     */
    public function testIteratorKey(HString $toIterate, $expected)
    {
        $this->assertEquals(0, $toIterate->key());

        for ($i = 0; $i < $expected; $i++) {
            $toIterate->next();
        }

        $this->assertEquals($expected, $toIterate->key());
    }

    public function iteratorKeyProvider()
    {
        return [
            'ASCII string to iterate' => [new HString('foobar'), 3],
            'UTF-8 string to iterate' => [new HString('ɹɐqooɟ'), 3],
        ];
    }

    /**
     * @dataProvider arrayStyleCountProvider
     *
     * @param HString $toCount
     * @param integer $expected
     */
    public function testArrayStyleCount(HString $toCount, $expected)
    {
        $this->assertEquals($expected, $toCount->count());
    }

    public function arrayStyleCountProvider()
    {
        return [
            'ASCII string to iterate' => [new HString('foobar'), 6],
            'UTF-8 string to iterate' => [new HString('ɹɐqooɟ'), 6],
        ];
    }

    /**
     * @dataProvider arrayStyleOffsetExistsProvider
     *
     * @param HString $stringToTest
     */
    public function testArrayStyleOffsetExists(HString $stringToTest)
    {
        $expectedLastOffset = $stringToTest->count() - 1;

        $this->assertTrue(isset($stringToTest[$expectedLastOffset]));
        $this->assertFalse(isset($stringToTest[$expectedLastOffset + 1]));
    }

    public function arrayStyleOffsetExistsProvider()
    {
        return [
            'ASCII string to iterate' => [new HString('foobar')],
            'UTF-8 string to iterate' => [new HString('ɹɐqooɟ')],
        ];
    }

    /**
     * @dataProvider arrayStyleOffsetGetProvider
     *
     * @param HString $stringToTest
     * @param integer $offset
     * @param string $expected
     */
    public function testArrayStyleOffsetGet(HString $stringToTest, $offset, $expected)
    {
        $this->assertEquals($expected, $stringToTest[$offset]);
    }

    public function arrayStyleOffsetGetProvider()
    {
        return [
            'ASCII string to iterate' => [new HString('foobar'), 3, 'b'],
            'UTF-8 string to iterate' => [new HString('ɹɐqooɟ'), 3, 'o'],
        ];
    }

    /**
     * @dataProvider arrayStyleOffsetSetProvider
     *
     * @param HString $stringToTest
     * @param integer $offset
     * @param string $char
     * @param HString $expected
     */
    public function testArrayStyleOffsetSet(HString $stringToTest, $offset, $char, $expected)
    {
        $stringToTest[$offset] = $char;
        $this->assertEquals($expected, $stringToTest);
    }

    public function arrayStyleOffsetSetProvider()
    {
        return [
            'ASCII string to iterate' => [new HString('foobar'), 0, 'r', new HString('roobar')],
            'UTF-8 string to iterate' => [new HString('ɹɐqooɟ'), 0, 'ɟ', new HString('ɟɐqooɟ')],
        ];
    }

    public function testArrayStyleOffsetUnset()
    {
        unset($this->utf8String[3]);
        $this->assertEquals(chr(0x00), $this->utf8String[3]); // binary null
    }

    public function testArrayStyleAccess()
    {
        $this->assertEquals('o', $this->aString[1]);
        $this->assertEquals('ɐ', $this->utf8String[1]);
    }

    public function testStringHead()
    {
        $this->assertEquals('f', $this->aString->head()->toString());
        $this->assertEquals('ɹ', $this->utf8String->head()->toString());

        $emptyString = new HString();
        $this->assertEmpty(sprintf($emptyString->head()));
    }

    public function testStringTail()
    {
        $this->assertEquals('oobar', $this->aString->tail()->toString());

        $emptyString = new HString();
        $this->assertEmpty(sprintf($emptyString->tail()));
    }

    /**
     * @dataProvider sumStringProvider
     *
     * @param \Haystack\HString $hString
     * @param               $expected
     */
    public function testStringSum(HString $hString, $expected)
    {
        $this->assertEquals($expected, $hString->sum());
    }

    public function sumStringProvider()
    {
        return [
            'Empty HString' => [new HString(), 0],
            'HString of chars' => [new HString($this->aString), 0],
            'HString of chars & spaces' => [new HString('foo bar baz'), 0],
            'HString of comma-delimited ints' => [new HString('1, 2, 3, 4, 5, 6, 7, 8, 9, 10'), 55],
            'HString of comma-delimited ints & doubles' => [new HString('1.1, 2, 3, 4, 5, 6, 7, 8, 9, 10'), 55.1],
        ];
    }

    /**
     * @dataProvider productStringProvider
     *
     * @param HString $hString
     * @param $expected
     */
    public function testStringProvider(HString $hString, $expected)
    {
        $this->assertEquals($expected, $hString->product());
    }

    public function productStringProvider()
    {
        return [
            'Empty HString' => [new HString(), 0],
            'HString of chars' => [new HString($this->aString), 0],
            'HString of chars & spaces' => [new HString('foo bar baz'), 0],
            'HString of chars & ints' => [new HString('1, 2, 3, 4, 5, 6, 7, 8, 9, 10, apple'), 0],
            'HString of comma-delimited ints' => [new HString('1, 2, 3, 4, 5, 6, 7, 8, 9, 10'), 3628800],
            'HString of comma-delimited ints & doubles' => [new HString('1.1, 2, 3, 4, 5, 6, 7, 8, 9, 10'), 3991680],
        ];
    }
}
