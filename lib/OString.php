<?php
namespace OPHP;

class OString implements Container, SimpleMath
{
    private $string;

    public function __construct($string)
    {
        if (is_scalar($string)) {
            $this->string = (string) $string;
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
}
