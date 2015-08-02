<?php
namespace OPHP;

use OPHP\Helpers\Helper;

class OArray extends \ArrayObject implements ContainerInterface, BaseFunctionalInterface, MathInterface
{
    const USE_KEY = "key";
    const USE_BOTH = "both";

    /** @var array */
    private $arr;

    /** @var  Helper */
    private $helper;

    public function __construct($arr = null)
    {
        $this->helper = new Helper();

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
            throw new \ErrorException("{$this->helper->getType($arr)} cannot be instantiated as an OArray");
        }
    }

    public function toArray()
    {
        return $this->arr;
    }

    public function toOString($glue = "")
    {
        if (empty($this->arr)) {
            return new OString();
        }

        $string = new ToOString($this->arr, $glue);
        return new OString($string->toString());
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function contains($value)
    {
        $answer = new OArrayContains($this, $value);
        return $answer->isContained();
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     *
     * @param $value
     * @return OArray
     * @throws \InvalidArgumentException
     */
    public function append($value)
    {
        if ($value instanceof OArray) {
            $value = $value->toArray();
        }
        if ($this->helper->canBeInArray($value)) {
            $array = new OArray($this);
            parent::append($value);

            return $array;
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} cannot be appended to an OArray");
        }
    }

    /**
     * @inheritdoc
     *
     * @param          $value
     * @param int|null $key
     * @return OArray
     *
     * @throws \InvalidArgumentException
     */
    public function insert($value, $key = null)
    {
        if ($value instanceof OArray) {
            $valueArray = $value->toArray();
        } elseif ($value instanceof OString) {
            $valueArray = $value->toString();
        } elseif ($this->helper->canBeInArray($value)) {
            $valueArray = $value;
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} cannot be contained within an OArray");
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

        return new OArray(array_merge_recursive($first, (array) $array, $last));
    }


    /**
     * @inheritdoc
     *
     * @param $value
     * @return OArray
     * @throws \InvalidArgumentException
     */
    public function remove($value)
    {
        if ($this->helper->canBeInArray($value)) {
            if (!$this->contains($value)) {
                return new OArray($this->arr);
            }

            $newArr = $this->arr;
            $key = $this->locate($value);
        } else {
            throw new \InvalidArgumentException("{$this->helper->getType($value)} cannot be contained within an OArray");
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
     * @inheritdoc
     *
     * @param $start
     * @param $length
     * @return OArray
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
     * @inheritdoc
     *
     * @param callable $func
     * @return OArray
     */
    public function map(callable $func)
    {
        return new OArray(array_map($func, $this->arr));
    }

    /**
     * @inheritdoc
     *
     * @param callable $func
     * @return null
     */
    public function walk(callable $func)
    {
        array_walk($this->arr, $func);
    }

    /**
     * @inheritdoc
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
     * @return bool|float|int|string|OString|array|\ArrayObject|OArray
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
     * @inheritdoc
     *
     * @return OArray
     */
    public function head()
    {
        return $this->slice(0, 1);
    }

    /**
     * @inheritdoc
     *
     * @return OArray
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
        return array_sum($this->arr);
    }

    /**
     * @inheritdoc
     *
     * @return int|number
     */
    public function product()
    {
        if (empty($this->arr)) {
            return 0;
        }

        return array_product($this->arr);
    }

    /**
     * @param $key
     * @param $value
     * @return array
     */
    private function setSubarrayAndLengthForSequentialArray($key, $value)
    {
        $array = $value;
        $length = (int) $key;

        return array($array, $length);
    }

    /**
     * @param string $key
     * @param        $value
     * @return array
     */
    private function setSubarrayAndLengthForAssociativeArray($key, $value)
    {
        $array = [$key => $value];
        $length = sizeof($this->arr);

        return array($array, $length);
    }

    /**
     * @param $value
     * @return array
     */
    private function setSubarrayAndLengthWhenNoKeyProvided($value)
    {
        $array = $value;
        $length = sizeof($this->arr);

        return array($array, $length);
    }

    /**
     * @param callable $func
     * @return OArray
     */
    private function filterWithKey(callable $func)
    {
        $newArr = new OArray();
        foreach ($this as $key => $value) {
            if (true === (bool) $func($key)) {
                $newArr = $newArr->insert($value, $key);
            }
        }

        return $newArr;
    }

    /**
     * @param callable $func
     * @return OArray
     */
    private function filterWithValueAndKey(callable $func)
    {
        $newArr = new OArray();
        foreach ($this as $key => $value) {
            if (true === (bool) $func($value, $key)) {
                $newArr = $newArr->insert($value, $key);
            }
        }

        return $newArr;
    }
}
