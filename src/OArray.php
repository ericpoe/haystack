<?php
namespace OPHP;

use OPHP\Helpers\Helper;

class OArray extends \ArrayObject implements ContainerInterface, BaseFunctionalInterface, MathInterface
{
    const USE_KEY = "key";
    const USE_BOTH = "both";

    /** @var array */
    protected $arr;

    /** @var  Helper */
    private $helper;

    public function __construct($arr = null)
    {
        $this->helper = new Helper();

        if (is_null($arr)) {
            parent::__construct();
            $this->arr = [];
        } elseif (is_array($arr)) {
            parent::__construct($arr);
            $this->arr = $arr;
        } elseif ($arr instanceof \ArrayObject) {
            parent::__construct($arr);
            $this->arr = $arr->getArrayCopy();
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

    /**
     * Alias to PHP function `implode`
     *
     * @param string $glue - defaults to an empty string
     * @return OString
     */
    public function toOString($glue = "")
    {
        if (empty($this->arr)) {
            return new OString();
        }

        $string = new OArrayToString($this->arr, $glue);
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
        $answer = new OArrayContains($this);
        return $answer->contains($value);
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @return int - array-notation location of $value in current object; "-1" if not found
     */
    public function locate($value)
    {
        $answer = new OArrayLocate($this);
        return $answer->locate($value);
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
        $answer = new OArrayAppend($this->toArray());
        return new OArray($answer->append($value));
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
        $answer = new OArrayInsert($this);
        return new OArray($answer->insert($value, $key));
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
        $answer = new OArrayRemove($this);
        return new OArray($answer->remove($value));
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
        $answer = new OArraySlice($this);
        return new OArray($answer->slice($start, $length));
    }

    /**
     * @inheritdoc
     *
     * @param callable $func
     * @return OArray
     */
    public function map(callable $func)
    {
        $answer = new OArrayMap($this);
        return new OArray($answer->map($func));
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
        $answer = new OArrayFilter($this);
        return new OArray($answer->filter($func, $flag));
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
}
