<?php
namespace Haystack;

use Haystack\Container\ContainerInterface;
use Haystack\Container\HaystackArrayAppend;
use Haystack\Container\HaystackArrayContains;
use Haystack\Container\HaystackArrayInsert;
use Haystack\Container\HaystackArrayLocate;
use Haystack\Container\HaystackArrayRemove;
use Haystack\Container\HaystackArraySlice;
use Haystack\Converter\ArrayToString;
use Haystack\Functional\FunctionalInterface;
use Haystack\Functional\HArrayFilter;
use Haystack\Functional\HArrayMap;
use Haystack\Functional\HArrayReduce;
use Haystack\Functional\HArrayWalk;
use Haystack\Helpers\Helper;
use Haystack\Math\MathInterface;

class HArray extends \ArrayObject implements ContainerInterface, FunctionalInterface, MathInterface
{
    const USE_KEY = "key";
    const USE_BOTH = "both";

    /** @var array */
    protected $arr;

    public function __construct($arr = null)
    {
        if (is_null($arr)) {
            parent::__construct();
            $this->arr = [];
        } elseif (is_array($arr)) {
            parent::__construct($arr);
            $this->arr = $arr;
        } elseif ($arr instanceof \ArrayObject) {
            parent::__construct($arr);
            $this->arr = $arr->getArrayCopy();
        } elseif ($arr instanceof HString) {
            parent::__construct();
            $this->arr = [$arr->toString()];
        } elseif (is_scalar($arr)) {
            parent::__construct();
            $this->arr = [$arr];
        } else {
            throw new \ErrorException(sprintf("%s cannot be instantiated as an HArray", Helper::getType($arr)));
        }
    }

    public function toArray()
    {
        return $this->arr;
    }

    /**
     * Alias to PHP function `implode`
     *
     * @param string $glue - defaults to an empty string
     * @return HString
     */
    public function toHString($glue = "")
    {
        if (empty($this->arr)) {
            return new HString();
        }

        $string = new ArrayToString($this->arr, $glue);
        return new HString($string->toString());
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function contains($value)
    {
        $answer = new HaystackArrayContains($this);
        return $answer->contains($value);
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return int - array-notation location of $value in current object; "-1" if not found
     */
    public function locate($value)
    {
        $answer = new HaystackArrayLocate($this);
        return $answer->locate($value);
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return HArray
     * @throws \InvalidArgumentException
     */
    public function append($value)
    {
        $answer = new HaystackArrayAppend($this->toArray());
        return new static($answer->append($value));
    }

    /**
     * @inheritdoc
     *
     * @param          $value
     * @param int|null $key
     * @return HArray
     *
     * @throws \InvalidArgumentException
     */
    public function insert($value, $key = null)
    {
        $answer = new HaystackArrayInsert($this);
        return new static($answer->insert($value, $key));
    }


    /**
     * @inheritdoc
     *
     * @param $value
     * @return HArray
     * @throws \InvalidArgumentException
     */
    public function remove($value)
    {
        $answer = new HaystackArrayRemove($this);
        return new static($answer->remove($value));
    }

    /**
     * @inheritdoc
     *
     * @param $start
     * @param $length
     * @return HArray
     * @throws \InvalidArgumentException
     */
    public function slice($start, $length = null)
    {
        $answer = new HaystackArraySlice($this);
        return new static($answer->slice($start, $length));
    }

    /**
     * @inheritdoc
     *
     * @param callable $func
     * @return HArray
     */
    public function map(callable $func)
    {
        $answer = new HArrayMap($this);
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
        HArrayWalk::walk($this->arr, $func);
    }

    /**
     * @inheritdoc
     *
     * @param callable $func   - If no callback is supplied, all entries of container equal to FALSE will be removed.
     * @param null     $flag   - Flag determining what arguments are sent to callback
     *                             - USE_KEY
     *                                 - pass key as the only argument to callback instead of the value
     *                             - USE_BOTH
     *                                 - pass both value and key as arguments to callback instead of the value
     *                                 - Requires PHP >= 5.6
     *
     * @return HArray
     *
     * @throws \InvalidArgumentException
     */
    public function filter(callable $func = null, $flag = null)
    {
        $answer = new HArrayFilter($this);
        return new static($answer->filter($func, $flag));
    }

    /**
     * @inheritdoc
     *
     * @param callable $func
     * @param mixed|null $initial
     * @return bool|float|int|HString|HArray
     */
    public function reduce(callable $func, $initial = null)
    {
        $answer = new HArrayReduce($this);
        return $answer->reduce($func, $initial);
    }

    /**
     * @inheritdoc
     *
     * @return HArray
     */
    public function head()
    {
        return $this->slice(0, 1);
    }

    /**
     * @inheritdoc
     *
     * @return HArray
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
        return array_sum($this->arr);
    }

    /**
     * @inheritdoc
     *
     * @return int|number
     */
    public function product()
    {
        if (empty($this->arr)) {
            return 0;
        }

        return array_product($this->arr);
    }
}
