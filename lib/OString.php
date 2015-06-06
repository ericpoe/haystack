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

    protected function getType($thing)
    {
        $type = gettype($thing);
        if ('object' === $type) {
            $type = get_class($thing);
        }
        return $type;
    }
}
