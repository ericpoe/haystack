<?php
namespace OPHP;

/**
 * Class OArray
 * @package OPHP
 */
class OArray extends \ArrayObject implements Container, SimpleMath, BaseFunctional
{
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
        } elseif (is_array($thing) || is_scalar($thing) || is_string($thing)) {
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

    public function map(callable $func)
    {
        return new OArray(array_map($func, $this->arr));
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
