<?php
namespace OPHP;

use OPHP\Helpers\Helper;

class OString implements \Iterator, \ArrayAccess, \Serializable, \Countable, Container, BaseFunctional, Math
{
    const USE_KEY = "key";
    const USE_BOTH = "both";
    private $string;
    private $ptr; // pointer for iterating through $string

    /** @var  Helper */
    private $helper;

    /**
     * @param null $string
     * @throws \ErrorException
     */
    public function __construct($string = null)
    {
        $this->helper = new Helper();

        if (is_scalar($string) || $string instanceof OString) {
            $this->string = (string) $string;
            $this->rewind();
        } elseif (is_null($string)) {
            $this->string = null;
        } else {
            throw new \ErrorException("{$this->helper->getType($string)} is not a proper String");
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf($this->string);
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
     * Alias to PHP function `explode`
     *
     * @param string $delim
     * @param int    $limit
     * @return OArray
     * @throws \InvalidArgumentException
     */
    public function toOArray($delim = " ", $limit = null)
    {
        if (empty($this->string)) {
            return new OArray();
        }

        $arr = new ToOArray($this->string, $delim, $limit);
        return new OArray($arr->toArray());
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
        $answer = new OStringContains($this, $value);
        return $answer->isContained();
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return int - location of $value in current object; "-1" if not found
     * @throws \InvalidArgumentException
     */
    public function locate($value)
    {
        if (is_scalar($value)) {
            return $this->contains($value) ? strpos($this->string, (string) $value) : -1;
        }

        if ($value instanceof OString) {
            return $this->contains($value) ? strpos($this->string, $value->toString()) : -1;
        }

        throw new \InvalidArgumentException("{$this->helper->getType($value)} is neither a scalar value nor an OString");
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return OString
     * @throws \InvalidArgumentException
     */
    public function append($value)
    {
        if (is_scalar($value) || $value instanceof OString) {
            return new OString($this->string . $value);
        }
        throw new \InvalidArgumentException("Cannot concatenate an OString with a {$this->helper->getType($value)}");
    }

    /**
     * @inheritdoc
     *
     * @param          $value
     * @param int|null $key
     * @return OString
     * @throws \InvalidArgumentException
     */
    public function insert($value, $key = null)
    {
        if (is_scalar($value) || $value instanceof OString) {
            if (is_null($key)) {
                $key = strlen($this);
            } elseif (is_numeric($key)) {
                $key = (int) $key;
            } else {
                throw new \InvalidArgumentException("Invalid array key");
            }


            return new OString(substr_replace($this->string, $value, $key, 0));
        }

        throw new \InvalidArgumentException("Cannot insert {$this->helper->getType($value)} into an OString");
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return OString
     */
    public function remove($value)
    {
        $key = $this->locate($value);
        $startString = $this->slice(0, $key);
        $endString = $this->slice($key + 1);

        return $startString->insert($endString);
    }

    /**
     * @inheritdoc
     *
     * @param $start
     * @param $length
     * @return OString
     * @throws \InvalidArgumentException
     */
    public function slice($start, $length = null)
    {
        if (is_null($start) || !is_numeric($start)) {
            throw new \InvalidArgumentException("Slice parameter 1, \$start, must be an integer");
        }

        if (!is_null($length) && !is_numeric($length)) {
            throw new \InvalidArgumentException("Slice parameter 2, \$length, must be null or an integer");
        }

        if (is_null($length)) {
            return new OString((substr($this, $start)));
        }

        return new OString(substr($this->string, $start, $length));
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
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
        return isset($this->string[$offset]);
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
        return $this->string[$offset];
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
        $this->string[$offset] = $value;
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
        $this->string[$offset] = null;
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
        return serialize($this->toString());
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
            $this->string = unserialize($value);
        } elseif (is_null($value)) {
            $this->string = null;
        } else {
            throw new \InvalidArgumentException("OString cannot unserialize a {$this->helper->getType($value)}");
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
        return strlen($this->string);
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
        return $this->string[$this->ptr];
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


    /**
     * @inheritdoc
     *
     * @param callable $func
     * @return OString
     */
    public function map(callable $func)
    {
        $newString = new OString($this->string);

        $size = $this->count();
        for ($i = 0; $i < $size; $i++) {
            $newString[$i] = $func($this[$i]);
        }

        return $newString;
    }

    /**
     * @inheritdoc
     *
     * @param callable $func
     * @return null
     */
    public function walk(callable $func)
    {
        $size = $this->count();

        for ($i = 0; $i < $size; $i++) {
            $this[$i] = $func($this[$i], $i);
        }
    }

    /**
     * @inheritdoc
     *
     * @return OString
     *
     * @throws \InvalidArgumentException
     */
    public function filter(callable $func = null, $flag = null)
    {
        // Default
        if (is_null($func)) {
            return $this->filterWithDefaults();
        }

        // No flags are passed
        if (is_null($flag)) {
            return $this->filterWithValue($func);
        }

        // Flag is passed
        if ("key" === $flag || "both" === $flag) {
            // Flag of "USE_KEY" is passed
            if ("key" === $flag) {
                return $this->filterWithKey($func);
            }

            // Flag of "USE_BOTH is passed
            return $this->filterWithValueAndKey($func);
        }
        throw new \InvalidArgumentException("Invalid flag name");
    }

    /**
     * @inheritdoc
     *
     * @param callable $func
     * @param null     $initial
     * @return bool|float|int|string|OString|array|\ArrayObject|OArray
     */
    public function reduce(callable $func, $initial = null)
    {
        // todo: figure out invalid types, if any, of $initial
        $reduced = $initial;
        foreach ($this as $letter) {
            $reduced = $func($reduced, $letter);
        }

        if ($reduced instanceof \ArrayObject || is_array($reduced)) {
            return new OArray($reduced);
        }

        if (is_string($reduced)) {
            return new OString($reduced);
        }

        return $reduced;
    }

    /**
     * @inheritdoc
     *
     * @return OString
     */
    public function head()
    {
        return $this->slice(0, 1);
    }

    /**
     * @inheritdoc
     *
     * @return OString
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
        $values = new OArray(str_getcsv(str_ireplace(" ", "", $this->string)));

        return $values->sum();
    }

    /**
     * @inheritdoc
     *
     * @return number
     */
    public function product()
    {
        $values = new OArray(str_getcsv(str_ireplace(" ", "", $this->string)));

        return $values->product();
    }

    /**
     * @return OString
     */
    private function filterWithDefaults()
    {
        $newString = new OString();

        foreach ($this as $letter) {
            if ((bool) $letter) {
                $newString = $newString->insert($letter);
            }
        }

        return $newString;
    }

    /**
     * @param callable $func
     * @return OString
     */
    private function filterWithValue(callable $func)
    {
        $newString = new OString();

        foreach ($this as $letter) {
            if ($func($letter)) {
                $newString = $newString->insert($letter);
            }
        }

        return $newString;
    }

    /**
     * @param callable $func
     * @return OString
     */
    private function filterWithKey(callable $func)
    {
        $newString = new OString();

        foreach ($this as $letter) {
            if (true === (bool) $func($this->key())) {
                $newString = $newString->insert($letter);
            }
        }

        return $newString;
    }

    /**
     * @param callable $func
     * @return OString
     */
    private function filterWithValueAndKey(callable $func)
    {
        $newString = new OString();

        foreach ($this as $letter) {
            if (true === (bool) $func($letter, $this->key())) {
                $newString = $newString->insert($letter);
            }
        }

        return $newString;
    }
}
