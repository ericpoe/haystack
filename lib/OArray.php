<?php
namespace OPHP;

/**
 * Class OArray
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
        if (isset($arr)) {
            parent::__construct($arr);
        } else {
            parent::__construct();
        }

        if (is_null($arr) || is_array($arr) || $arr instanceof OArray) {
            $this->arr = $arr;
        } elseif (is_scalar($arr)) {
            $this->arr = [$arr];
        } else {
            throw new \ErrorException("$arr is of type $this->getType($arr) and cannot be instantiated as an OArray");
        }
    }

    public function toArray()
    {
        return $this->arr;
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
        } elseif (is_array($thing) || is_scalar($thing)) {
            $thingArray = $thing;
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
        $last =  $this->slice($length, $lastStartingPoint)->toArray();


        return new OArray(array_merge_recursive($first, (array) $array, $last));
    }


    /**
     * @param $thing
     * @return \OPHP\OArray
     * @throws \ErrorException
     */
    public function remove($thing)
    {
        if (!$this->contains($thing)) {
            throw new \ErrorException("$thing does not exist within the OArray");
        }

        $newArr = $this->arr;
        $key = $this->locate($thing);


        if (is_numeric($key)) {
            unset($newArr[$key]);
            return new OArray(array_values($newArr));
        } elseif (is_string($key)) {
            unset($newArr[$key]);
            return new OArray($newArr);
        } else {
            throw new \ErrorException("Invalid array key");
        }
    }

    /**
     * @param $start
     * @param $length
     * @return mixed
     */
    public function slice($start, $length = null)
    {
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
     * Iterates over each value in the array passing them to the callback function. If the callback function returns
     * true, the current value from array is returned into the result array. Array keys are preserved.
     *
     * @param callable $func   - If no callback is supplied, all entries of array equal to FALSE will be removed.
     * @param null     $flag   - Flag determining what arguments are sent to callback
     *                         * USE_KEY - pass key as the only argument to callback instead of the value
     *                         * USE_BOTH - pass both value and key as arguments to callback instead of the value
     *                                    - Requires PHP >= 5.6
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
                } else {
                    throw new \ErrorException('filter flag of "USE_BOTH" is not supported prior to PHP 5.6');
                }
            }
        } else {
                throw new \ErrorException("Bad flag name");
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
