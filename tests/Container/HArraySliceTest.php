<?php
namespace Haystack\Tests\Container;

use Haystack\HArray;
use PHPUnit\Framework\TestCase;

class HArraySliceTest extends TestCase
{
    /** @var HArray */
    private $arrList;
    /** @var HArray */
    private $arrDict;

    protected function setUp()
    {
        $this->arrList = new HArray(['apple', 'bobble', 'cobble', 'dobble']);
        $this->arrDict = new HArray(['a' => 'apple', 'b' => 'bobble', 'c' => 'cobble', 'd' => 'dobble']);
    }

    /**
     * @dataProvider firstPartOfArraySliceProvider
     *
     * @param string $type
     * @param HArray $expected
     */
    public function testGetFirstPartOfTypesOfArrayUsingSlice($type, HArray $expected)
    {
        if ('list' === $type) {
            $subArray = $this->arrList->slice(0, 2);
        } else {
            $subArray = $this->arrDict->slice(0, 2);
        }

        $this->assertEquals($expected, $subArray);
    }

    public function firstPartOfArraySliceProvider()
    {
        return [
            'First two items of list' => ['list', new HArray(['apple', 'bobble'])],
            'First two items of dictionary' => ['dict', new HArray(['a' => 'apple', 'b' => 'bobble'])],
        ];
    }

    /**
     * @dataProvider lastPartOfArraySliceProvider
     *
     * @param string $type
     * @param HArray $expected
     */
    public function testGetLastPartOfTypesOfArrayUsingSlice($type, HArray $expected)
    {
        if ('list' === $type) {
            $subArray = $this->arrList->slice(-2);
        } else {
            $subArray = $this->arrDict->slice(-2);
        }

        $this->assertEquals($expected, $subArray);
    }

    public function lastPartOfArraySliceProvider()
    {
        return [
            'Last two items of list' => ['list', new HArray(['cobble', 'dobble'])],
            'Last two items of dictionary' => ['dict', new HArray(['c' => 'cobble', 'd' => 'dobble'])],
        ];
    }

    /**
     * @dataProvider middlePartOfArraySliceProvider
     *
     * @param string $type
     * @param int $start
     * @param int $length
     * @param HArray $expected
     */
    public function testGetMiddlePartOfTypesOfArrayUsingSlice($type, $start, $length, HArray $expected)
    {
        if ('list' === $type) {
            $subArray = $this->arrList->slice($start, $length);
        } else {
            $subArray = $this->arrDict->slice($start, $length);
        }

        $this->assertEquals($expected, $subArray);
    }

    public function middlePartOfArraySliceProvider()
    {
        return [
            'List: Start -3, length: -1' => ['list', '-3', '-1', new HArray(['bobble', 'cobble'])],
            'List: Start 1, length: -1' => ['list', '1', '-1', new HArray(['bobble', 'cobble'])],
            'List: Start 1, length: 2' => ['list', '1', '2', new HArray(['bobble', 'cobble'])],
            'List: Start 1, length: null' => ['list', '1', null, new HArray(['bobble', 'cobble', 'dobble'])],
            'Dictionary: Start -3, length: -1' => ['dict', '-3', '-1', new HArray(['b' => 'bobble', 'c' => 'cobble'])],
            'Dictionary: Start 1, length: -1' => ['dict', '1', '-1', new HArray(['b' => 'bobble', 'c' => 'cobble'])],
            'Dictionary: Start 1, length: 2' => ['dict', '1', '2', new HArray(['b' => 'bobble', 'c' => 'cobble'])],
            'Dictionary: Start 1, length: null' => ['dict', '1', null, new HArray(['b' => 'bobble', 'c' => 'cobble', 'd' => 'dobble'])],
        ];
    }
}
