<?php
namespace OPHP;

/**
 * Class OArray
 * @package OPHP
 *
 * todo: implement \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
 */
class OArray extends \ArrayObject implements Container, SimpleMath
{
    /** @var OArray array */
    private $arr;

    public function __construct($arr)
    {
        parent::__construct($arr);

        if (is_array($arr) || $arr instanceof OArray) {
            $this->arr = $arr;
        } elseif (is_scalar($arr)) {
            $this->arr = [$arr];
        } else {
            throw new \ErrorException("$arr is of type $this->getType($arr) and cannot be instantiated as an OArray");
        }
    }

    public function contains($thing)
    {
        if (is_scalar($thing) || is_object($thing)) {
            return (in_array($thing, $this->arr));
        }
    }

    public function locate($thing)
    {
        if (is_scalar($thing)  || is_object($thing)) {
            $key = array_search($thing, $this->arr);

            return (false !== $key) ? $key : -1;
        }
    }

    /**
     * Concatenates two things of the same type.
     *
     * @param $thing
     * @return OArray
     * @throws \ErrorException
     */
    public function append($thing)
    {
        if (is_scalar($thing)) {
            $array = new OArray($this);
            parent::append($thing);
            return $array;
        } elseif (is_array($thing) || is_object($thing)) {
            $array = new OArray($this);
            $array->offsetSet(null, $thing);
            return $array;
        } else {
            throw new \ErrorException("Cannot concatenate an OArray with a {$this->getType($thing)}");
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
