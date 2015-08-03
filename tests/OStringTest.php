<?php
namespace OPHP\Tests;

use OPHP\OArray;
use OPHP\OString;

class OStringTest extends \PHPUnit_Framework_TestCase
{
    /** @var \OPHP\Ostring */
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
     * @param $message
     */
    public function testCreateBadOstringOfThings($item, $message)
    {
        $this->setExpectedException("ErrorException", $message);
        $this->aString = new OString($item);
    }

    public function createBadOStringProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime is not a proper String"],
            "SPL Object" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList is not a proper String"],
        ];
    }

    /**
     * @dataProvider stringLocateProvider()
     *
     * @param $checkString
     * @param $expected
     */
    public function testLocateTypesOfStringInFoobar($checkString, $expected)
    {
        $var = $this->aString->locate($checkString);
        $this->assertEquals($expected, $var);
    }

    public function stringLocateProvider()
    {
        return [
            "String known-present" => ["oob", 1],
            "String known-missing" => ["baz", -1],
            "OString known-present" => [new OString('oob'), 1],
            "OString known-missing" => [new OString('baz'), -1],
            "Integer known-missing" => [42, -1],
            "OString integer known-missing" => [new OString(42), -1],

        ];
    }

    /**
     * @dataProvider badLocateTypesOfStringInFoobarProvider
     * @param $item
     * @param $message
     * @throws \InvalidArgumentException
     */
    public function testBadLocateTypesOfStringInFoobar($item, $message)
    {
        $this->setExpectedException("InvalidArgumentException", $message);
        $var = $this->aString->locate($item);
    }

    public function badLocateTypesOfStringInFoobarProvider()
    {
        return [
            "DateTime" => [new \DateTime(), "DateTime is neither a scalar value nor an OString"],
            "SplDoublyLinkedList" => [new \SplDoublyLinkedList(), "SplDoublyLinkedList is neither a scalar value nor an OString"],
        ];
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
        ];
    }

    /**
     * @dataProvider providerFirstPartOfTypesOfStringUsingSlice
     *
     * @param $expected
     */
    public function testGetFirstPartOfTypesOfStringUsingSlice($expected)
    {

        $this->assertEquals($expected, $this->aString->slice(0, 4));

    }

    public function providerFirstPartOfTypesOfStringUsingSlice()
    {
        return [
            "String" => ["foob"],
            "OString" => [new OString("foob")],
        ];
    }

    /**
     * @dataProvider providerLastPartOfTypesOfStringUsingSlice
     *
     * @param $expected
     */
    public function testGetLastPartOfTypesOfStringUsingSlice($expected)
    {
        $this->assertEquals($expected, $this->aString->slice(-4));
    }

    public function providerLastPartOfTypesOfStringUsingSlice()
    {
        return [
            "String" => ["obar"],
            "OString" => [new OString("obar")],
        ];
    }

    /**
     * @dataProvider middlePartOfStringProvider
     *
     * @param $start
     * @param $finish
     * @param $expected
     */
    public function testGetMiddlePartOfTypesOfStringUsingSlice($start, $finish, $expected)
    {
        $this->assertEquals($expected, $this->aString->slice($start, $finish));
    }

    public function middlePartOfStringProvider()
    {
        return [
            "String: Negative finish, middle" => [2, -2, "ob"],
            "String: Negative start & finish, middle" => [-4, -2, "ob"],
            "String: normal middle" => [2, 2, "ob"],
            "String: null finish" => [2, null, "obar"],
            "String: overflow finish" => [2, 2000, "obar"],
            "OString: Negative finish, middle" => [2, -2, new OString("ob")],
            "OString: Negative start & finish, middle" => [-4, -2, new OString("ob")],
            "OString: normal middle" => [2, 2, new OString("ob")],
            "OString: null finish" => [2, null, new OString("obar")],
            "OString: overflow finish" => [2, 2000, new OString("obar")],
        ];
    }

    /**
     * @dataProvider badSlicingProvider()
     *
     * @param $start
     * @param $length
     * @param $message
     */
    public function testBadSlicing($start, $length, $message)
    {
        $this->setExpectedException("InvalidArgumentException", $message);
        $tmp = $this->aString->slice($start, $length);
    }

    public function badSlicingProvider()
    {
        return [
            "No start or length of slice" => [null, null, "Slice parameter 1, \$start, must be an integer"],
            "Non-integer start of slice" => ["cat", 4, "Slice parameter 1, \$start, must be an integer"],
            "Non-integer length of slice" => ["1", "dog", "Slice parameter 2, \$length, must be null or an integer"],
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
     * @param $message
     */
    public function testBadUnserialize($item, $message)
    {
        $this->setExpectedException("InvalidArgumentException", $message);
        $this->aString->unserialize($item);
    }

    public function badUnserializeProvider()
    {
        return [
            "Unserialized OString" => [new OString("The quick brown fox"), "OString cannot unserialize a OPHP\\OString"],
            "DateTime object" => [new \DateTime(), "OString cannot unserialize a DateTime"],
        ];
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
     * @param $message
     * @throws \InvalidArgumentException
     */
    public function testBadInsert($value, $key, $message)
    {
        $this->setExpectedException("InvalidArgumentException", $message);
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

    public function testTypesOfStringRemove()
    {
        $newString = $this->aString->remove("o");
        $this->assertEquals(new OString("fobar"), $newString);
    }

    public function testNonScalarTypeCannotBeAddedToFoobar()
    {
        $this->setExpectedException("InvalidArgumentException", "Cannot concatenate an OString with a DateTime");
        $newString = $this->aString->append(new \DateTime());
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

    public function testStringMap()
    {
        $capitalize = function ($letter) {
            return strtoupper($letter);
        };

        $newString = $this->aString->map($capitalize);

        $this->assertEquals("FOOBAR", $newString);
    }

    public function testStringWalk()
    {
        $capitalize = function ($letter, $key) {
            return $this->aString[$key] = strtoupper($letter);
        };

        $this->aString->walk($capitalize);

        $this->assertEquals("FOOBAR", $this->aString->toString());
    }

    public function testReduce()
    {
        $fn = function ($carry, $item) {
            $value = (ord(strtolower($item)) - 64);
            return $carry + $value;
        };

        $this->assertEquals(249, $this->aString->reduce($fn));
    }

    public function testOStringReduce()
    {
        $encode = function ($carry, $item) {
            $value = (ord($item) % 26) + 97;
            $carry .= chr($value);

            return $carry;
        };

        $decode = function ($carry, $item) {
            $value = ((ord($item) + 14) % 26) + 97;
            $carry .= chr($value);

            return $carry;
        };

        $codedMessage = new OString("yhhutk");

        $this->assertEquals($codedMessage, $this->aString->reduce($encode));
        $this->assertEquals("foobar", $codedMessage->reduce($decode));
        $this->assertTrue($this->aString->reduce($encode) instanceof OString);
    }

    /**
     * @dataProvider stringReduceAsArrayTypeProvider
     * @param $freq
     */
    public function testStringReduceAsArrayTypeReturnsOarray($freq)
    {
        $this->assertTrue($this->aString->reduce($freq) instanceof OArray);
    }

    public function stringReduceAsArrayTypeProvider()
    {
        $freqArray = function ($frequency, $letter) {
            if (!isset($frequency[$letter])) {
                $frequency[$letter] = 0;
            }

            $frequency[$letter]++;

            return $frequency;
        };

        $freqArrayObject = function ($frequency, $letter) {
            if (!isset($frequency[$letter])) {
                $frequency[$letter] = 0;
            }

            $frequency = new \ArrayObject($frequency);

            $frequency[$letter]++;

            return $frequency;
        };

        $freqOArray = function ($frequency, $letter) {
            if (!isset($frequency[$letter])) {
                $frequency[$letter] = 0;
            }

            $frequency = new OArray($frequency);

            $frequency[$letter]++;

            return $frequency;
        };

        return [
            "Array" => [$freqArray],
            "ArrayObject" => [$freqArrayObject],
            "OArray" => [$freqOArray],
        ];
    }

    /**
     * @dataProvider stringReduceWithInitialValueProvider
     *
     * @param OString $string
     * @param         $initial
     * @param         $expected
     */
    public function testStringReduceWithInitialValue(OString $string, $initial, $expected)
    {
        $what = function ($carry, $item) {
            $carry .= $item;

            return $carry;
        };

        $this->assertEquals($expected, $string->reduce($what, $initial));
    }

    public function stringReduceWithInitialValueProvider()
    {
        return [
            "Empty OString" => [new OString(), "alone", "alone"],
            "OString" => [new OString("present"), "The ", "The present"],
        ];
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
