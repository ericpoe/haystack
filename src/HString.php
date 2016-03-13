<?php
namespace Haystack;

use Haystack\Container\ContainerInterface;
use Haystack\Container\ElementNotFoundException;
use Haystack\Container\HaystackStringAppend;
use Haystack\Container\HaystackStringContains;
use Haystack\Container\HaystackStringInsert;
use Haystack\Container\HaystackStringLocate;
use Haystack\Container\HaystackStringRemove;
use Haystack\Container\HaystackStringSlice;
use Haystack\Converter\StringToArray;
use Haystack\Functional\FunctionalInterface;
use Haystack\Functional\HStringFilter;
use Haystack\Functional\HStringMap;
use Haystack\Functional\HStringReduce;
use Haystack\Functional\HStringWalk;
use Haystack\Math\MathInterface;

class HString extends BaseHString implements ContainerInterface, FunctionalInterface, MathInterface
{
    const USE_KEY = "key";
    const USE_BOTH = "both";

    /**
     * alias to __toString()
     *
     * @return string
     */
    public function toString()
    {
        return $this->__toString();
    }

    /**
     * Alias to PHP function `explode`
     *
     * @param string $delim
     * @param int    $limit
     * @return HArray
     * @throws \InvalidArgumentException
     */
    public function toHArray($delim = " ", $limit = null)
    {
        if (empty($this->string)) {
            return new HArray();
        }

        $arr = new StringToArray($this->string, $delim);
        return new HArray($arr->stringToArray($limit));
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function contains($value)
    {
        $answer = new HaystackStringContains($this);
        return $answer->contains($value);
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return int - location of $value in current object
     * @throws ElementNotFoundException
     * @throws \InvalidArgumentException
     */
    public function locate($value)
    {
        $answer = new HaystackStringLocate($this);
        return $answer->locate($value);
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return HString
     * @throws \InvalidArgumentException
     */
    public function append($value)
    {
        $answer = new HaystackStringAppend($this);
        return new static($answer->append($value));
    }

    /**
     * @inheritdoc
     *
     * @param          $value
     * @param int|null $key
     * @return HString
     * @throws \InvalidArgumentException
     */
    public function insert($value, $key = null)
    {
        $answer = new HaystackStringInsert($this);
        return new static($answer->insert($value, $key));
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return HString
     * @throws \InvalidArgumentException
     */
    public function remove($value)
    {
        $answer = new HaystackStringRemove($this);
        return new static($answer->remove($value));
    }

    /**
     * @inheritdoc
     *
     * @param $start
     * @param $length
     * @return HString
     * @throws \InvalidArgumentException
     */
    public function slice($start, $length = null)
    {
        $answer = new HaystackStringSlice($this);
        return new static($answer->slice($start, $length));
    }

    /**
     * @inheritdoc
     *
     * @param callable $func
     * @return HString
     */
    public function map(callable $func)
    {
        $answer = new HStringMap($this);
        return new static($answer->map($func));
    }

    /**
     * @inheritdoc
     *
     * @param callable $func
     * @return null
     */
    public function walk(callable $func)
    {
        HStringWalk::walk($this, $func);
    }

    /**
     * @inheritdoc
     *
     * @return HString
     *
     * @throws \InvalidArgumentException
     */
    public function filter(callable $func = null, $flag = null)
    {
        $answer =  new HStringFilter($this);
        return new static($answer->filter($func, $flag));
    }

    /**
     * @inheritdoc
     *
     * @param callable $func
     * @param mixed $initial
     * @return bool|float|int|HString|HArray
     */
    public function reduce(callable $func, $initial = null)
    {
        $answer = new HStringReduce($this);
        return $answer->reduce($func, $initial);
    }

    /**
     * @inheritdoc
     *
     * @return HString
     */
    public function head()
    {
        return $this->slice(0, 1);
    }

    /**
     * @inheritdoc
     *
     * @return HString
     */
    public function tail()
    {
        return $this->slice(1);
    }

    /**
     * @inheritdoc
     *
     * @return number
     */
    public function sum()
    {
        return $this->toHArray()->sum();
    }

    /**
     * @inheritdoc
     *
     * @return number
     */
    public function product()
    {
        return $this->toHArray()->product();
    }
}
