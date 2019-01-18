<?php
namespace Haystack\Tests\Container;

use Haystack\Container\ElementNotFoundException;
use Haystack\HString;
use PHPUnit\Framework\TestCase;

class HStringLocateTest extends TestCase
{
    /**
     * @dataProvider stringLocateProvider()
     *
     * @param HString $target
     * @param string | HString $checkString
     * @param integer $expected
     */
    public function testLocateTypesOfStringInFoobar(HString $target, $checkString, $expected)
    {
        $var = $target->locate($checkString);
        $this->assertEquals($expected, $var);
    }

    public function stringLocateProvider()
    {
        $aString = new HString('foobar');
        $utf8String = new HString('ɹɐqooɟ');

        return [
            'ASCII: String known-present' => [$aString, 'oob', 1],
            'ASCII: HString known-present' => [$aString, new HString('oob'), 1],
            'UTF-8: String known-present' => [$utf8String, 'ɐqo', 1],
            'UTF-8: HString known-present' => [$utf8String, new HString('ɐqo'), 1],
        ];
    }

    public function testObjectWithString()
    {
        $date = new \DateTime('now');
        $timeStamp = $date->format('c');
        $timeSentence = new HString(sprintf('I have %s in me.', $timeStamp));
        $this->assertEquals(7, $timeSentence->locate($date->format('c')));

        // This would be a good use of a PHP7 anonymous class
        $obj = new ObjWithToString();
        $sampleString = "I'm a string";
        $objSentence = new HString(sprintf('I have %s in me.', $sampleString));
        $this->assertEquals(7, $objSentence->locate($obj));
    }

    /**
     * @dataProvider stringBadLocateProvider()
     *
     * @param        $checkString
     * @param string $message
     */
    public function testCannotLocateTypesOfStringInFoober($checkString, $message)
    {
        $this->expectException(ElementNotFoundException::class);
        $this->expectExceptionMessage($message);

        (new HString('foobar'))->locate($checkString);
    }

    public function stringBadLocateProvider()
    {
        return [
            'String known-missing' => ['baz', 'Element could not be found: baz'],
            'HString known-missing' => [new HString('baz'), 'Element could not be found: baz'],
            'Integer known-missing' => [42, 'Element could not be found: 42'],
            'HString integer known-missing' => [new HString(42), 'Element could not be found: 42'],
        ];
    }

    /**
     * @dataProvider badLocateTypesOfStringInFoobarProvider
     * @param $item
     * @param $exceptionMsg
     * @throws \InvalidArgumentException
     */
    public function testBadLocateTypesOfStringInFoobar($item, $exceptionMsg)
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage($exceptionMsg);

        (new HString('foobar'))->locate($item);
    }

    public function badLocateTypesOfStringInFoobarProvider()
    {
        return [
            'DateTime' => [
                new \DateTime(),
                'DateTime cannot be converted to a string; it cannot be used as a search value within an HString'
            ],
            'SplDoublyLinkedList' => [
                new \SplDoublyLinkedList(),
                'SplDoublyLinkedList cannot be converted to a string; it cannot be used as a search value within an HString'
            ],
        ];
    }
}
