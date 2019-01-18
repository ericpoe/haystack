<?php
namespace Haystack\Tests\Container;

use Haystack\HArray;
use PHPUnit\Framework\TestCase;

class HArrayRemoveTest extends TestCase
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
     * @dataProvider arrayRemoveProvider
     *
     * @param $type
     * @param $value
     * @param $expected
     */
    public function testArrayTypeRemove($type, $value, $expected)
    {
        if ('list' === $type) {
            $newArr = $this->arrList->remove($value);
        } else {
            $newArr = $this->arrDict->remove($value);
        }

        $this->assertEquals($expected, $newArr);

    }

    public function arrayRemoveProvider()
    {
        return [
            'List: Basic list' => ['list', 'bobble', new HArray(['apple', 'cobble', 'dobble'])],
            'List: Basic list - item not found' => ['list', 'zobble', new HArray(['apple', 'bobble', 'cobble', 'dobble'])],
            'Basic dict' => ['dict', 'bobble', new HArray(['a' => 'apple', 'c' => 'cobble', 'd' => 'dobble'])],
            'Basic dict - item not found' => ['dict', 'zobble', new HArray(['a' => 'apple', 'b' => 'bobble', 'c' => 'cobble', 'd' => 'dobble'])],
        ];
    }

    public function testArrayTypeRemoveObject()
    {
        $timestamp = new \DateTime();

        $arrList = $this->arrList->insert($timestamp, 2);
        $arrDict = $this->arrDict->insert($timestamp, 2);

        $this->assertEquals($this->arrList, $arrList->remove($timestamp), 'Object removed from list');
        $this->assertEquals($arrList, $arrList->remove(new \SplDoublyLinkedList()), 'Object not removed from list');
        $this->assertEquals($this->arrDict, $arrDict->remove($timestamp), 'Object removed from dict');
        $this->assertEquals($arrDict, $arrDict->remove(new \SplDoublyLinkedList()), 'Object not removed from dict');
    }

}
