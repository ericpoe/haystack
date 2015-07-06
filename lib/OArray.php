<?php
namespace OPHP;

/**
 * Class OArray
 *
 * @package OPHP
 */
class OArray extends \ArrayObject implements Container, BaseFunctional
{
    const USE_KEY = "key";
    const USE_BOTH = "both";

    /** @var OArray array */
    private $arr;

    public function __construct($arr = null)
    {
        if (is_null($arr)) {
            parent::__construct();
        } elseif (is_array($arr) || $arr instanceof \ArrayObject) {
            parent::__construct($arr);
            $this->arr = $arr;
        } elseif ($arr instanceof OString) {
            parent::__construct();
            $this->arr = $arr;
        } elseif (is_scalar($arr)) {
            parent::__construct();
            $this->arr = [$arr];
        } else {
            throw new \ErrorException("{$this->getType($arr)} cannot be instantiated as an OArray");
        }
    }

    public function toArray()
    {
        return $this->arr;
    }

    public function contains($thing)
    {
        if ($this->canBeInArray($thing)) {
            return (in_array($thing, $this->arr));
        } else {
            throw new \ErrorException("{$this->getType($thing)} cannot be contained within an OArray");
        }
    }

    public function locate($thing)
    {
        if ($this->canBeInArray($thing)) {
            $key = array_search($thing, $this->arr);

            return (false !== $key) ? $key : -1;
        } else {
            throw new \ErrorException("{$this->getType($thing)} cannot be contained within an OArray");
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
        if ($this->canBeInArray($thing)) {
            $array = new OArray($this);
            parent::append($thing);

            return $array;
        } else {
            throw new \ErrorException("{$this->getType($thing)} cannot be appended to an OArray");
        }
    }

    /**
     * Inserts a $thing at a specified location; if no location is provided, $thing will be added to the back.
     *
     * @param          $thing
     * @param int|null $key
     * @return mixed
     *
     * @throws \ErrorException
     */
    public function insert($thing, $key = null)
    {
        if ($thing instanceof OArray) {
            $thingArray = $thing->toArray();
        } elseif ($thing instanceof OString) {
            $thingArray = $thing->toString();
        } elseif ($this->canBeInArray($thing)) {
            $thingArray = $thing;
        } else {
            throw new \ErrorException("{$this->getType($thing)} cannot be contained within an OArray");
        }

        if (isset($key)) {
            if (is_numeric($key)) {
                list($array, $length) = $this->setSubarrayAndLengthForSequentialArray($key, $thingArray);
            } elseif (is_string($key)) {
                list($array, $length) = $this->setSubarrayAndLengthForAssociativeArray($key, $thingArray);
            } else {
                throw new \ErrorException("Invalid array key");
            }
        } else {
            list($array, $length) = $this->setSubarrayAndLengthWhenNoKeyProvided($thingArray);
        }

        $first = $this->slice(0, $length)->toArray();
        $lastStartingPoint = sizeof($this->arr) - sizeof($first);
        $last = $this->slice($length, $lastStartingPoint)->toArray();

        return new OArray(array_merge_recursive($first, (array)$array, $last));
    }


    /**
     * @param $thing
     * @return \OPHP\OArray
     * @throws \ErrorException
     */
    public function remove($thing)
    {
        if ($this->canBeInArray($thing)) {
            if (!$this->contains($thing)) {
                return new OArray($this->arr);
            }

            $newArr = $this->arr;
            $key = $this->locate($thing);
        } else {
            throw new \ErrorException("{$this->getType($thing)} cannot be contained within an OArray");
        }


        if (is_numeric($key)) {
            unset($newArr[$key]);

            return new OArray(array_values($newArr));
        }

        // key is string
        unset($newArr[$key]);

        return new OArray($newArr);
    }

    /**
     * @param $start
     * @param $length
     * @return mixed
     * @throws \ErrorException
     */
    public function slice($start, $length = null)
    {
        if (!is_numeric($start)) {
            throw new \ErrorException("Slice parameter 1, \$start, must be an integer");
        }

        if (!is_null($length) && !is_numeric($length)) {
            throw new \ErrorException("Slice parameter 2, \$length, must be null or an integer");
        }

        $maintainIndices = false;

        return new OArray(array_slice($this->arr, $start, $length, $maintainIndices));

    }

    /**
     * Applies the callback to the elements of the given array
     *
     * @param callable $func
     * @return OArray
     */
    public function map(callable $func)
    {
        return new OArray(array_map($func, $this->arr));
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
        array_walk($this->arr, $func);
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
     *
     * @return OArray
     *
     * @throws \ErrorException
     */
    public function filter(callable $func = null, $flag = null)
    {
        // Default
        if (is_null($func)) {
            return new OArray(array_filter($this->arr));
        }

        // No flags are passed
        if (is_null($flag)) {
            return new OArray(array_filter($this->arr, $func));
        }

        // Flags are USE_KEY or USE_BOTH
        if ("key" === $flag || "both" === $flag) {
            if ("key" === $flag) {
                return new OArray(array_filter($this->arr, $func, ARRAY_FILTER_USE_KEY));
            }
            if ("both" === $flag) {
                if (5.6 <= substr(phpversion(), 0, 3)) {
                    return new OArray(array_filter($this->arr, $func, ARRAY_FILTER_USE_BOTH));
                }
                throw new \ErrorException('filter flag of "USE_BOTH" is not supported prior to PHP 5.6');
            }
        } else {
            throw new \ErrorException("Invalid flag name");
        }
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

    protected function canBeInArray($thing)
    {
        $possibility = is_array($thing)
            || is_scalar($thing)
            || $thing instanceof \ArrayObject
            || $thing instanceof OString;

        return $possibility;
    }

    /**
     * @param $key
     * @param $thingArray
     * @return array
     */
    protected function setSubarrayAndLengthForSequentialArray($key, $thingArray)
    {
        $array = $thingArray;
        $length = (int)$key;

        return array($array, $length);
    }

    /**
     * @param $key
     * @param $thingArray
     * @return array
     */
    protected function setSubarrayAndLengthForAssociativeArray($key, $thingArray)
    {
        $array = [$key => $thingArray];
        $length = sizeof($this->arr);

        return array($array, $length);
    }

    /**
     * @param $thingArray
     * @return array
     */
    protected function setSubarrayAndLengthWhenNoKeyProvided($thingArray)
    {
        $array = $thingArray;
        $length = sizeof($this->arr);

        return array($array, $length);
    }
}
