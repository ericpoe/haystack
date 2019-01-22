<?php
namespace Haystack\Tests\Functional;

use Haystack\HString;
use PHPUnit\Framework\TestCase;

class HStringWalkTest extends TestCase
{
    /** @var HString */
    protected $aString;

    protected function setUp()
    {
        $this->aString = new HString('foobar');
    }

    public function testStringWalk()
    {
        $this->aString->walk(function ($letter, $key) {
            return $this->aString[$key] = strtoupper($letter);
        });

        $this->assertEquals('FOOBAR', $this->aString->toString());
    }
}
