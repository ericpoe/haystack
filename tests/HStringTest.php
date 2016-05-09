<?php
namespace Haystack\Tests;

use Haystack\HArray;
use Haystack\HString;

class HStringTest extends \PHPUnit_Framework_TestCase
{
    /** @var HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString("foobar");
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
            "Empty String" => [" ", " "],
            "HString" => [new HString("abc"), "abc"],
            "HString of HString of HString of..." => [new HString(new HString(new HString(new HString("abc")))), "abc"],
            "Simple string" => ["abc", "abc"],
            "integer 1" => [1, "1"],
            "integer 0" => [0, "0"],
            "double 1.1" => [1.1, "1.1"],
            "DateTime formatted timestamp" => [$timeStamp->format('c'), $timeStamp->format('c')],
            "boolean true" => [true, "1"],
            "boolean false" => [false, ""],
            "Blank string" => ["", ""],
            "Null string" => [null, ""],
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
        $this->setExpectedException("ErrorException", $exceptionMsg);

        $this->aString = new HString($item);
    }

    public function createBadHStringProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime is not a proper String"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList is not a proper String"],
        ];
    }

    public function testSerialize()
    {
        $serialized = $this->aString->serialize();
        $this->assertEquals(serialize($this->aString->toString()), $serialized);
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
            "String" => [serialize($this->aString), new HString($this->aString)],
            "String with spaces" => [serialize("The quick brown fox jumps"), new HString("The quick brown fox jumps")],
            "Null string" => [serialize(null), new HString()],
            "Unserialized null string" => [null, new HString()],
        ];
    }

    /**
     * @dataProvider badUnserializeProvider
     * @param $item
     * @param $exceptionMsg
     */
    public function testBadUnserialize($item, $exceptionMsg)
    {
        $this->setExpectedException("InvalidArgumentException", $exceptionMsg);

        $this->aString->unserialize($item);
    }

    public function badUnserializeProvider()
    {
        return [
            "Unserialized HString" => [new HString("The quick brown fox"), "HString cannot unserialize a Haystack\\HString"],
            "DateTime object" => [new \DateTime(), "HString cannot unserialize a DateTime"],
        ];
    }

    public function testIteratorNext()
    {
        $this->aString->next();
        $this->assertEquals("o", $this->aString->current());
    }

    public function testIteratorValid()
    {
        $this->aString->next(); // "o"
        $this->aString->next(); // "o"
        $this->aString->next(); // "b"
        $this->aString->next(); // "a"
        $this->aString->next(); // "r"
        $this->assertTrue($this->aString->valid());
        $this->aString->next(); // uninitialized string offset
        $this->assertFalse($this->aString->valid());
    }

    public function testIteratorRewind()
    {
        $this->aString->next(); // "o"
        $this->aString->next(); // "o"
        $this->aString->next(); // 'b"

        $this->aString->rewind(); // back to "f"
        $this->assertEquals("f", $this->aString->current());
    }

    public function testIteratorKey()
    {
        $this->aString->next(); // "o"
        $this->aString->next(); // "o"
        $this->aString->next(); // 'b"

        $this->assertEquals(3, $this->aString->key());
    }

    public function testArrayStyleCount()
    {
        $this->assertEquals(6, $this->aString->count());
    }

    public function testArrayStyleOffsetExists()
    {
        $this->assertTrue(isset($this->aString[3]));
        $this->assertFalse(isset($this->aString[30]));
    }

    public function testArrayStyleOffsetGet()
    {
        $this->assertEquals("b", $this->aString[3]);
    }

    public function testArrayStyleOffsetSet()
    {
        $this->aString[0] = "b";
        $this->assertEquals(new HString("boobar"), $this->aString);
    }

    public function testArrayStyleOffsetUnset()
    {
        unset($this->aString[3]);
        $this->assertEquals(chr(0x00), $this->aString[3]); // binary null
    }

    public function testArrayStyleAccess()
    {
        $this->assertEquals("o", $this->aString[1]);
    }

    public function testStringHead()
    {
        $this->assertEquals("f", $this->aString->head()->toString());

        $emptyString = new HString();
        $this->assertEmpty(sprintf($emptyString->head()));
    }

    public function testStringTail()
    {
        $this->assertEquals("oobar", $this->aString->tail()->toString());

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
            "Empty HString" => [new HString(), 0],
            "HString of chars" => [new HString($this->aString), 0],
            "HString of chars & spaces" => [new HString("foo bar baz"), 0],
            "HString of comma-delimited ints" => [new HString("1, 2, 3, 4, 5, 6, 7, 8, 9, 10"), 55],
            "HString of comma-delimited ints & doubles" => [new HString("1.1, 2, 3, 4, 5, 6, 7, 8, 9, 10"), 55.1],
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
            "Empty HString" => [new HString(), 0],
            "HString of chars" => [new HString($this->aString), 0],
            "HString of chars & spaces" => [new HString("foo bar baz"), 0],
            "HString of chars & ints" => [new HString("1, 2, 3, 4, 5, 6, 7, 8, 9, 10, apple"), 0],
            "HString of comma-delimited ints" => [new HString("1, 2, 3, 4, 5, 6, 7, 8, 9, 10"), 3628800],
            "HString of comma-delimited ints & doubles" => [new HString("1.1, 2, 3, 4, 5, 6, 7, 8, 9, 10"), 3991680],
        ];
    }
}
