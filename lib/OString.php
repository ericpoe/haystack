<?php
namespace OPHP;

class OString implements \Iterator, \ArrayAccess, \Serializable, \Countable, Container, SimpleMath
{
    private $string;
    private $ptr; // pointer for iterating through $string

    public function __construct($string)
    {
        if (is_scalar($string)) {
            $this->string = (string) $string;
            $this->rewind();
        } else {
            throw new \ErrorException("$string is not a proper String");
        }
    }

    public function __toString()
    {
        return sprintf($this->string);
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function contains($thing)
    {
        if (is_scalar($thing)) {
            $pos = strstr($this->string, (string) $thing);
            return (false !== $pos) ?: false;
        } elseif ($thing instanceof OString) {
            $pos = strstr($this->string, sprintf("%s", $thing));
            return (false !== $pos) ?: false;
        } else {
            throw new \ErrorException("$thing is neither a proper String nor an OString");
        }
    }

    /**
     * Finds the first location of $thing
     * @param $thing
     * @return int position of $thing, -1 if not found
     * @throws \ErrorException
     */
    public function locate($thing)
    {
        if (is_scalar($thing)) {
            $pos = strpos($this->string, (string) $thing);
            return (false !== $pos) ? $pos : -1;
        } elseif ($thing instanceof OString) {
            $pos = strpos($this->string, sprintf("%s", $thing));
            return (false !== $pos) ? $pos : -1;
        } else {
            throw new \ErrorException("$thing is neither a proper String nor an OString");
        }

    }

    /**
     * Concatenates two things of the same type.
     *
     * @param $thing
     * @return OString
     * @throws \ErrorException
     */
    public function append($thing)
    {
        if (is_string($thing) || $thing instanceof OString) {
            return new OString($this->string . $thing);
        } else {
            throw new \ErrorException("Cannot concatenate an OString with a {$this->getType($thing)}");
        }
    }

    /**
     * Inserts a $thing at a specified location; if no location is provided, $thing will be added to the back.
     *
     * @param $thing
     * @param int|null $key
     * @return mixed
     */
    public function insert($thing, $key = null)
    {
        if (is_string($thing) || $thing instanceof OString) {
            if (!isset ($key)) {
                $key = strlen($this);
            }

            return new OString(substr_replace($this->string, $thing, $key, 0));
        }
    }

    /**
     * @param $thing
     * @return mixed
     */
    public function remove($thing)
    {
        // TODO: Implement remove() method.
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
                return substr($this, $start, $length);
            } else {
                throw new \InvalidArgumentException("Start value and Length value must both be integers");
            }
        } elseif (is_int($start)) {
            return substr($this, $start);
        } else {
            throw new \InvalidArgumentException("Start value must be an integer");
        }
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
}
