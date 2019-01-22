<?php
namespace Haystack\Tests\Container;

use Haystack\HArray;
use PHPUnit\Framework\TestCase;

class HArrayAppendTest extends TestCase
{
    /** @var  HArray */
    private $arrList;

    /** @var  HArray */
    private $arrDict;

    protected function setUp()
    {
        $this->arrList = new HArray(['apple', 'bobble', 'cobble', 'dobble']);
        $this->arrDict = new HArray(['a' => 'apple', 'b' => 'bobble', 'c' => 'cobble', 'd' => 'dobble']);
    }

    /**
     * @dataProvider appendProvider
     *
     * @param $type
     * @param $newThing
     * @param $expected
     */
    public function testAppendStringInArray($type, $newThing, $expected)
    {
        if ('list' === $type) {
            $newArray = $this->arrList->append($newThing);
        } else {
            $newArray = $this->arrDict->append($newThing);
        }

        $this->assertEquals($expected, $newArray);
    }

    public function appendProvider()
    {
        $dateTime = new \DateTime();

        return [
            'String to list' => ['list', 'ebble', new HArray(['apple', 'bobble', 'cobble', 'dobble', 'ebble'])],
            'String array to list' => ['list', ['ebble'], new HArray(['apple', 'bobble', 'cobble', 'dobble', ['ebble']])],
            'String HArray to list' => ['list', new HArray(['ebble']), new HArray(['apple', 'bobble', 'cobble', 'dobble', ['ebble']])],
            'Object to list' => ['list', $dateTime, new HArray(['apple', 'bobble', 'cobble', 'dobble', $dateTime])],
            'String to dictionary' => ['dict', 'ebble', new HArray(['a' => 'apple', 'b' => 'bobble', 'c' => 'cobble', 'd' => 'dobble', '0' => 'ebble'])],
            'String array to dictionary' => ['dict', ['e' => 'ebble'], new HArray(['a' => 'apple', 'b' => 'bobble', 'c' => 'cobble', 'd' => 'dobble', ['e' => 'ebble']])],
            'String HArray to dictionary' => ['dict', new HArray(['e' => 'ebble']), new HArray(['a' => 'apple', 'b' => 'bobble', 'c' => 'cobble', 'd' => 'dobble', ['e' => 'ebble']])],
            'Object to dictionary' => ['dict', $dateTime, new HArray(['a' => 'apple', 'b' => 'bobble', 'c' => 'cobble', 'd' => 'dobble', 0 => $dateTime])],
        ];
    }
}
