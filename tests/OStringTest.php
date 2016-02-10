<?php
namespace OPHP\Tests;

use OPHP\OArray;
use OPHP\OString;

class OStringTest extends \PHPUnit_Framework_TestCase
{
    /** @var OString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new OString("foobar");
    }

    public function testCreateEmptyString()
    {
        $emptyString = new OString();
        $this->assertEmpty($emptyString);
    }

    /**
     * @dataProvider stringOfThingsProvider
     *
     * @param $item
     * @param $expected
     */
    public function testCreateOStringOfThings($item, $expected)
    {
        $this->aString = new OString($item);
        $this->assertEquals($expected, $this->aString);
    }

    public function stringOfThingsProvider()
    {
        $timeStamp = new \DateTime();

        return [
            "Empty String" => [" ", " "],
            "OString" => [new OString("abc"), "abc"],
            "OString of OString of OString of..." => [new OString(new OString(new OString(new OString("abc")))), "abc"],
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
     * @dataProvider createBadOStringProvider
     *
     * @param $item
     * @param $exceptionMsg
     */
    public function testCreateBadOstringOfThings($item, $exceptionMsg)
    {
        $this->expectException("ErrorException");
        $this->expectExceptionMessage($exceptionMsg);

        $this->aString = new OString($item);
    }

    public function createBadOStringProvider()
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
     * @param $string
     * @param $expected
     */
    public function testUnserialize($string, $expected)
    {
        $this->aString->unserialize($string);
        $this->assertEquals($expected, $this->aString);
    }

    public function unserializeProvider()
    {
        return [
            "String" => [serialize($this->aString), new OString($this->aString)],
            "String with spaces" => [serialize("The quick brown fox jumps"), new OString("The quick brown fox jumps")],
            "Null string" => [serialize(null), new OString()],
            "Unserialized null string" => [null, new OString()],
        ];
    }

    /**
     * @dataProvider badUnserializeProvider
     * @param $item
     * @param $exceptionMsg
     */
    public function testBadUnserialize($item, $exceptionMsg)
    {
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage($exceptionMsg);

        $this->aString->unserialize($item);
    }

    public function badUnserializeProvider()
    {
        return [
            "Unserialized OString" => [new OString("The quick brown fox"), "OString cannot unserialize a OPHP\\OString"],
            "DateTime object" => [new \DateTime(), "OString cannot unserialize a DateTime"],
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
        $this->assertEquals(new OString("boobar"), $this->aString);
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

        $emptyString = new OString();
        $this->assertEmpty(sprintf($emptyString->head()));
    }

    public function testStringTail()
    {
        $this->assertEquals("oobar", $this->aString->tail()->toString());

        $emptyString = new OString();
        $this->assertEmpty(sprintf($emptyString->tail()));
    }

    /**
     * @dataProvider sumStringProvider
     *
     * @param \OPHP\OString $string
     * @param               $expected
     */
    public function testStringSum(OString $string, $expected)
    {
        $this->assertEquals($expected, $string->sum());
    }

    public function sumStringProvider()
    {
        return [
            "Empty OString" => [new OString(), 0],
            "OString of chars" => [new OString($this->aString), 0],
            "OString of chars & spaces" => [new OString("foo bar baz"), 0],
            "OString of comma-delimited ints" => [new OString("1, 2, 3, 4, 5, 6, 7, 8, 9, 10"), 55],
            "OString of comma-delimited ints & doubles" => [new OString("1.1, 2, 3, 4, 5, 6, 7, 8, 9, 10"), 55.1],
        ];
    }

    /**
     * @dataProvider productStringProvider
     *
     * @param \OPHP\OString $string
     * @param               $expected
     */
    public function testStringProvider(OString $string, $expected)
    {
        $this->assertEquals($expected, $string->product());
    }

    public function productStringProvider()
    {
        return [
            "Empty OString" => [new OString(), 0],
            "OString of chars" => [new OString($this->aString), 0],
            "OString of chars & spaces" => [new OString("foo bar baz"), 0],
            "OString of chars & ints" => [new OString("1, 2, 3, 4, 5, 6, 7, 8, 9, 10, apple"), 0],
            "OString of comma-delimited ints" => [new OString("1, 2, 3, 4, 5, 6, 7, 8, 9, 10"), 3628800],
            "OString of comma-delimited ints & doubles" => [new OString("1.1, 2, 3, 4, 5, 6, 7, 8, 9, 10"), 3991680],
        ];
    }
}
