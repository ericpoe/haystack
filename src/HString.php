<?php
namespace Haystack;

use Haystack\Container\ContainerInterface;
use Haystack\Container\ElementNotFoundException;
use Haystack\Container\HStringAppend;
use Haystack\Container\HStringContains;
use Haystack\Container\HStringInsert;
use Haystack\Container\HStringLocate;
use Haystack\Container\HStringRemove;
use Haystack\Container\HStringSlice;
use Haystack\Converter\StringToArray;
use Haystack\Functional\Filter;
use Haystack\Functional\FunctionalInterface;
use Haystack\Functional\HaystackMap;
use Haystack\Functional\HaystackReduce;
use Haystack\Functional\HStringWalk;
use Haystack\Helpers\Helper;
use Haystack\Math\MathInterface;

class HString implements \Iterator, \ArrayAccess, \Serializable, \Countable, ContainerInterface, FunctionalInterface, MathInterface
{
    const USE_KEY = 'key';
    const USE_BOTH = 'both';

    protected $str;
    protected $ptr; // pointer for iterating through $str
    protected $encoding; // defaults to UTF-8 encoding

    public function __construct($str = '')
    {
        $this->encoding = 'UTF-8';

        if (is_scalar($str) || $str instanceof self) {
            $this->str = mb_convert_encoding($str, $this->encoding);
            $this->rewind();
        } elseif (is_null($str)) {
            $this->str = '';
        } else {
            throw new \ErrorException(sprintf('%s is not a proper String', Helper::getType($str)));
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->str;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether an offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     * @return boolean true on success or false on failure.
     *                      </p>
     *                      <p>
     *                      The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $offset >= 0 && $offset < $this->count();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return mb_substr($this->str, $offset, 1, $this->encoding);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->str = $this->getPrefix($offset) . $value . $this->getSuffix($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->str = $this->getPrefix($offset) . chr(0x00) . $this->getSuffix($offset);
    }

    private function getPrefix($length)
    {
        return mb_substr($this, 0, $length, $this->getEncoding());
    }

    private function getSuffix($start)
    {
        return mb_substr($this, $start + 1, $this->count() - $start, $this->getEncoding());
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     *
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->__toString());
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $value <p>
     *                           The string representation of the object.
     *                           </p>
     * @return void
     */
    public function unserialize($value)
    {
        if (is_scalar($value)) {
            $this->str = unserialize($value);
        } elseif (is_null($value)) {
            $this->str = '';
        } else {
            throw new \InvalidArgumentException(sprintf('HString cannot unserialize a %s', Helper::getType($value)));
        }
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *       </p>
     *       <p>
     *       The return value is cast to an integer.
     */
    public function count()
    {
        return mb_strlen($this->str);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return mb_substr($this->str, $this->ptr, 1, $this->encoding);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        ++$this->ptr;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return integer scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->ptr;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *       Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->ptr < $this->count();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->ptr = 0;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

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
     * Converts a string into an array. Assumes a delimiter of "" to return an array of chars.
     *
     * @return array
     */
    public function toArray()
    {
        if (empty($this->str)) {
            return [];
        }

        return (new StringToArray($this->str))
            ->stringToArray();
    }

    /**
     * Alias to PHP function `explode`
     *
     * @param string $delim
     * @param null|int    $limit
     * @return HArray
     * @throws \InvalidArgumentException
     */
    public function toHArray($delim = '', $limit = null)
    {
        if (empty($this->str)) {
            return new HArray();
        }

        $arr = new StringToArray($this->str, $delim);
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
        $answer = new HStringContains($this);
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
        $answer = new HStringLocate($this);
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
        $answer = new HStringAppend($this);
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
        $answer = new HStringInsert($this);
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
        $answer = new HStringRemove($this);
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
        $answer = new HStringSlice($this);
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
        $containers = array_slice(func_get_args(), 1); // remove `$func`
        $haystack = $this->toHArray();

        if (empty($containers)) {
            return new static((new HArray((new HaystackMap($haystack))->map($func)))->toHString());
        }

        return new static((new HArray((new HaystackMap($haystack))->map($func, $containers)))->toHString());
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
        $answer = new Filter($this->toHArray());
        return new static((new HArray($answer->filter($func, $flag)))->toHString());
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
        $answer = new HaystackReduce($this->toArray());
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
        $values = new HArray(str_getcsv(str_ireplace(' ', '', $this->str)));

        return $values->sum();
    }

    /**
     * @inheritdoc
     *
     * @return number
     */
    public function product()
    {
        $values = new HArray(str_getcsv(str_ireplace(' ', '', $this->str)));

        return $values->product();
    }
}
