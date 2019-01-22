<?php
namespace Haystack;

use Haystack\Container\ContainerInterface;
use Haystack\Container\ElementNotFoundException;
use Haystack\Container\HArrayAppend;
use Haystack\Container\HArrayContains;
use Haystack\Container\HArrayInsert;
use Haystack\Container\HArrayLocate;
use Haystack\Container\HArrayRemove;
use Haystack\Container\HArraySlice;
use Haystack\Converter\ArrayToString;
use Haystack\Functional\Filter;
use Haystack\Functional\FunctionalInterface;
use Haystack\Functional\HArrayWalk;
use Haystack\Functional\HaystackMap;
use Haystack\Functional\HaystackReduce;
use Haystack\Math\MathInterface;

class HArray extends \ArrayObject implements ContainerInterface, FunctionalInterface, MathInterface
{
    const USE_KEY = 'key';
    const USE_BOTH = 'both';

    /** @var array */
    protected $arr;

    /**
     * @param null|array|object|\ArrayObject|HString|bool|int|float|string $arr
     */
    public function __construct($arr = [])
    {
        if ($arr instanceof \ArrayObject) {
            $arr = $arr->getArrayCopy();
        }

        if ($arr instanceof HString) {
            $arr = [$arr->toString()];
        }

        if (is_scalar($arr) || is_object($arr)) {
            parent::__construct([$arr]);
            $this->arr = [$arr];
        } else {
            parent::__construct((array) $arr);
            $this->arr = (array) $arr;
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
    public function toHString($glue = '')
    {
        if (empty($this->arr)) {
            return new HString();
        }

        $str = new ArrayToString($this->arr, $glue);
        return new HString($str->toString());
    }

    /**
     * @inheritdoc
     *
     * @param mixed $value
     * @return boolean
     */
    public function contains($value)
    {
        $answer = new HArrayContains($this);
        return $answer->contains($value);
    }

    /**
     * @inheritdoc
     *
     * @param mixed $value
     * @return int|string - array-notation location of $value in current object; "-1" if not found
     * @throws ElementNotFoundException
     */
    public function locate($value)
    {
        $answer = new HArrayLocate($this);
        return $answer->locate($value);
    }

    /**
     * @inheritdoc
     *
     * @param mixed $value
     * @return HArray
     */
    public function append($value)
    {
        $answer = new HArrayAppend($this->toArray());
        return new static($answer->append($value));
    }

    /**
     * @inheritdoc
     *
     * @param mixed    $value
     * @param int|null $key
     * @return HArray
     *
     * @throws \InvalidArgumentException
     */
    public function insert($value, $key = null)
    {
        $answer = new HArrayInsert($this);
        return new static($answer->insert($value, $key));
    }


    /**
     * @inheritdoc
     *
     * @param mixed $value
     * @return HArray
     */
    public function remove($value)
    {
        $answer = new HArrayRemove($this);
        return new static($answer->remove($value));
    }

    /**
     * @inheritdoc
     *
     * @param int $start
     * @param int $length
     * @return HArray
     * @throws \InvalidArgumentException
     */
    public function slice($start, $length = null)
    {
        $answer = new HArraySlice($this);
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
        $containers = array_slice(func_get_args(), 1); // remove `$func`

        if (empty($containers)) {
            return new static((new HaystackMap($this))->map($func));
        }

        return new static((new HaystackMap($this))->map($func, $containers));
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
     *
     * @return HArray
     *
     * @throws \InvalidArgumentException
     */
    public function filter(callable $func = null, $flag = null)
    {
        $answer = new Filter($this);
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
        $answer = new HaystackReduce($this->arr);
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
