<?php
namespace OPHP;

/**
 * Class OArray
 *
 * @package OPHP
 */
class OArray extends \ArrayObject implements Container, BaseFunctional, Math
{
    const USE_KEY = "key";
    const USE_BOTH = "both";

    /** @var OArray array */
    private $arr;

    public function __construct($arr = null)
    {
        if (is_null($arr)) {
            parent::__construct();
            $this->arr = [];
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

    /**
     * Determines if a $value is in the current object.
     *
     * @param $value
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function contains($value)
    {
        if ($this->canBeInArray($value)) {
            return (in_array($value, $this->arr));
        } else {
            throw new \InvalidArgumentException("{$this->getType($value)} cannot be contained within an OArray");
        }
    }

    /**
     * Finds the location of $value in the current object. If it does not exist, the user will be notified
     *
     * @param $value
     * @return int - array-notation location of $value in current object; "-1" if not found
     */
    public function locate($value)
    {
        if ($this->contains($value)) {
            $key = array_search($value, $this->arr);
        } else {
            $key = -1;
        }

        return $key;
    }

    /**
     * Concatenates two things of the same type.
     *
     * @param $value
     * @return OArray
     * @throws \InvalidArgumentException
     */
    public function append($value)
    {
        if ($this->canBeInArray($value)) {
            $array = new OArray($this);
            parent::append($value);

            return $array;
        } else {
            throw new \InvalidArgumentException("{$this->getType($value)} cannot be appended to an OArray");
        }
    }

    /**
     * Inserts a $value at a specified location; if no location is provided, $value will be added to the back.
     *
     * @param          $value
     * @param int|null $key
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function insert($value, $key = null)
    {
        if ($value instanceof OArray) {
            $valueArray = $value->toArray();
        } elseif ($value instanceof OString) {
            $valueArray = $value->toString();
        } elseif ($this->canBeInArray($value)) {
            $valueArray = $value;
        } else {
            throw new \InvalidArgumentException("{$this->getType($value)} cannot be contained within an OArray");
        }

        if (isset($key)) {
            if (is_numeric($key)) {
                list($array, $length) = $this->setSubarrayAndLengthForSequentialArray($key, $valueArray);
            } elseif (is_string($key)) {
                list($array, $length) = $this->setSubarrayAndLengthForAssociativeArray($key, $valueArray);
            } else {
                throw new \InvalidArgumentException("Invalid array key");
            }
        } else {
            list($array, $length) = $this->setSubarrayAndLengthWhenNoKeyProvided($valueArray);
        }

        $first = $this->slice(0, $length)->toArray();
        $lastStartingPoint = sizeof($this->arr) - sizeof($first);
        $last = $this->slice($length, $lastStartingPoint)->toArray();

        return new OArray(array_merge_recursive($first, (array)$array, $last));
    }


    /**
     * @param $value
     * @return \OPHP\OArray
     * @throws \InvalidArgumentException
     */
    public function remove($value)
    {
        if ($this->canBeInArray($value)) {
            if (!$this->contains($value)) {
                return new OArray($this->arr);
            }

            $newArr = $this->arr;
            $key = $this->locate($value);
        } else {
            throw new \InvalidArgumentException("{$this->getType($value)} cannot be contained within an OArray");
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
     * @throws \InvalidArgumentException
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
            // Flag of "USE_KEY" is passed
            if ("key" === $flag) {
                if (version_compare(phpversion(), 5.6) >= 0) {
                    return new OArray(array_filter($this->arr, $func, ARRAY_FILTER_USE_KEY));
                } else {
                    return $this->filterWithKey($func);
                }
            }
            // Flag of "USE_BOTH is passed
            if (version_compare(phpversion(), 5.6) >= 0) {
                return new OArray(array_filter($this->arr, $func, ARRAY_FILTER_USE_BOTH));
            } else {
                return $this->filterWithValueAndKey($func);
            }
        }
        throw new \InvalidArgumentException("Invalid flag name");
    }

    /**
     * @inheritdoc
     *
     * @param callable $func
     * @param null     $initial
     * @return mixed
     */
    public function reduce(callable $func, $initial = null)
    {
        // todo: figure out invalid types, if any, of $initial
        $reduced = array_reduce($this->arr, $func, $initial);

        if ($reduced instanceof \ArrayObject || is_array($reduced)) {
            return new OArray($reduced);
        }

        if (is_string($reduced)) {
            return new OString($reduced);
        }

        return $reduced;
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

    public function sum()
    {
        return array_sum($this->arr);
    }

    public function product()
    {
        return array_product($this->arr);
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
     * @param $value
     * @return array
     */
    protected function setSubarrayAndLengthForSequentialArray($key, $value)
    {
        $array = $value;
        $length = (int)$key;

        return array($array, $length);
    }

    /**
     * @param $key
     * @param $value
     * @return array
     */
    protected function setSubarrayAndLengthForAssociativeArray($key, $value)
    {
        $array = [$key => $value];
        $length = sizeof($this->arr);

        return array($array, $length);
    }

    /**
     * @param $value
     * @return array
     */
    protected function setSubarrayAndLengthWhenNoKeyProvided($value)
    {
        $array = $value;
        $length = sizeof($this->arr);

        return array($array, $length);
    }

    /**
     * @param callable $func
     * @return OArray
     */
    protected function filterWithKey(callable $func)
    {
        $newArr = new OArray();
        foreach ($this as $key => $value) {
            if (true === (bool)$func($key)) {
                $newArr = $newArr->insert($value, $key);
            }
        }

        return $newArr;
    }

    /**
     * @param callable $func
     * @return OArray
     */
    protected function filterWithValueAndKey(callable $func)
    {
        $newArr = new OArray();
        foreach ($this as $key => $value) {
            if (true === (bool)$func($value, $key)) {
                $newArr = $newArr->insert($value, $key);
            }
        }

        return $newArr;
    }
}
