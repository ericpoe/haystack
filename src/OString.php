<?php
namespace OPHP;

class OString implements \Iterator, \ArrayAccess, \Serializable, \Countable, Container, BaseFunctional
{
    const USE_KEY = "key";
    const USE_BOTH = "both";
    private $string;
    private $ptr; // pointer for iterating through $string

    /**
     * @param null $string
     * @throws \ErrorException
     */
    public function __construct($string = null)
    {
        if (is_scalar($string) || $string instanceof OString) {
            $this->string = (string)$string;
            $this->rewind();
        } elseif (is_null($string)) {
            $this->string = null;
        } else {
            throw new \ErrorException("{$this->getType($string)} is not a proper String");
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
     * Determines if a $value is in the current object.
     *
     * @param $value
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function contains($value)
    {
        if (is_scalar($value)) {
            $pos = strstr($this->string, (string)$value);

            return (false !== $pos) ?: false;
        }

        if ($value instanceof OString) {
            $pos = strstr($this->string, $value->toString());

            return (false !== $pos) ?: false;
        }
        throw new \InvalidArgumentException("{$this->getType($value)} is neither a scalar value nor an OString");
    }

    /**
     * Finds the location of $value in the current object. If it does not exist, the user will be notified
     *
     * @param $value
     * @return int - location of $value in current object; "-1" if not found
     * @throws \InvalidArgumentException
     */
    public function locate($value)
    {
        if (is_scalar($value)) {
            return $this->contains($value) ? strpos($this->string, (string)$value) : -1;
        }

        if ($value instanceof OString) {
            return $this->contains($value) ? strpos($this->string, $value->toString()) : -1;
        }

        throw new \InvalidArgumentException("{$this->getType($value)} is neither a scalar value nor an OString");
    }

    /**
     * Concatenates two things of the same type.
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
        throw new \InvalidArgumentException("Cannot concatenate an OString with a {$this->getType($value)}");
    }

    /**
     * Inserts a $value at a specified location; if no location is provided, $value will be added to the back.
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
                $key = (int)$key;
            } else {
                throw new \InvalidArgumentException("Invalid array key");
            }


            return new OString(substr_replace($this->string, $value, $key, 0));
        }

        throw new \InvalidArgumentException("Cannot insert {$this->getType($value)} into an OString");
    }

    /**
     * @param $value
     * @return mixed
     */
    public function remove($value)
    {
        $key = $this->locate($value);
        $startString = $this->slice(0, $key);
        $endString = $this->slice($key + 1);

        return $startString->insert($endString);
    }

    /**
     * @param $start
     * @param $length
     * @return mixed
     */
    public function slice($start, $length = null)
    {
        if (isset($length)) {
            if (is_int($start) && is_int($length)) {
                return new OString(substr($this, $start, $length));
            }
            throw new \InvalidArgumentException("Start value and Length value must both be integers");
        }

        if (is_int($start)) {
            return new OString(substr($this, $start));
        }
        throw new \InvalidArgumentException("Start value must be an integer");
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
        return $this->toString();
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->string = $serialized;
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
     * @return mixed scalar on success, or null on failure.
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
     * Applies the callback to the elements of the given array
     *
     * @param callable $func
     * @return OString
     */
    public function map(callable $func)
    {
        $newString = new OString($this->string);
        for ($i = 0; $i < $this->count(); $i++) {
            $newString[$i] = $func($this[$i]);
        }

        return $newString;
    }

    /**
     * Walk does an in-place update of items in the object.
     *
     * Since the update is in-place, this breaks the immutablity of OPHP objects. This is useful for very large
     * implementations of the OPHP where cloning the object would be memory intensive.
     *
     * @param callable $func
     * @return bool
     */
    public function walk(callable $func)
    {
        for ($i = 0; $i < $this->count(); $i++) {
            $this[$i] = $func($this[$i], $i);
        }
    }

    /**
     * Iterates over each value in the container passing them to the callback function. If the callback function returns
     * true, the current value from container is returned into the result container. Container keys are preserved.
     *
     * @param callable $func   - If no callback is supplied, all entries of container equal to FALSE will be removed.
     * @param null     $flag   - Flag determining what arguments are sent to callback
     *                             - USE_KEY
     *                                 - pass key as the only argument to callback instead of the value
     *                             - USE_BOTH
     *                                 - pass both value and key as arguments to callback instead of the value
     *                                 - Requires PHP >= 5.6
     * @return OString
     *
     * @throws \InvalidArgumentException
     */
    public function filter(callable $func = null, $flag = null)
    {
        $newString = new OString();

        // Default
        if (is_null($func)) {
            return $this->filterWithDefaults($newString);
        }

        // No flags are passed
        if (is_null($flag)) {
            return $this->filterWithValue($func, $newString);
        }

        // Flag is passed
        if ("key" === $flag || "both" === $flag) {
            // Flag of "USE_KEY" is passed
            if ("key" === $flag) {
                return $this->filterWithKey($func, $newString);
            }

            // Flag of "USE_BOTH is passed
            if ("both" === $flag) {
                return $this->filterWithValueAndKey($func, $newString);
            }
        }
        throw new \InvalidArgumentException("Invalid flag name");
    }

    /**
     * Shows the first element of the collection
     *
     * @return mixed
     */
    public function head()
    {
        return $this->slice(0, 1);
    }

    /**
     * Shows the collection that doesn't include the head
     *
     * @return mixed
     */
    public function tail()
    {
        return $this->slice(1);
    }


    protected function getType($thing)
    {
        $type = gettype($thing);
        if ('object' === $type) {
            $type = get_class($thing);
        }

        return $type;
    }

    /**
     * @param OString $newString
     * @return mixed
     */
    protected function filterWithDefaults(OString $newString)
    {
        foreach ($this as $letter) {
            if ((bool)$letter) {
                $newString = $newString->insert($letter);
            }
        }

        return $newString;
    }

    /**
     * @param callable $func
     * @param OString  $newString
     * @return mixed
     */
    protected function filterWithValue(callable $func, Ostring $newString)
    {
        foreach ($this as $letter) {
            if ($func($letter)) {
                $newString = $newString->insert($letter);
            }
        }

        return $newString;
    }

    /**
     * @param callable $func
     * @param OString  $newString
     * @return mixed
     */
    protected function filterWithKey(callable $func, Ostring $newString)
    {
        foreach ($this as $letter) {
            if (true === (bool)$func($this->key())) {
                $newString = $newString->insert($letter);
            }
        }

        return $newString;
    }

    /**
     * @param callable $func
     * @param OString  $newString
     * @return mixed
     */
    protected function filterWithValueAndKey(callable $func, OString $newString)
    {
        foreach ($this as $letter) {
            if (true === (bool)$func($letter, $this->key())) {
                $newString = $newString->insert($letter);
            }
        }

        return $newString;
    }
}
