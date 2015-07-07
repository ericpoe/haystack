<?php
namespace OPHP;

interface Container
{
    /**
     * Determines if a $value is in the current object.
     *
     * @param $value
     * @return boolean
     */
    public function contains($value);

    /**
     * Finds the location of $value in the current object. If it does not exist, the user will be notified
     *
     * @param $value
     * @return int - array-notation location of $value in current object; "-1" if not found
     */
    public function locate($value);

    /**
     * Concatenates two things of the same type.
     *
     * @param $value
     * @return mixed
     */
    public function append($value);

    /**
     * Inserts a $value at a specified location; if no key is provided, $value will be added to the back.
     *
     * @param          $value
     * @param int|null $key
     * @return mixed
     */
    public function insert($value, $key = null);

    /**
     * @param $value
     * @return mixed
     */
    public function remove($value);

    /**
     * @param $start
     * @param $length
     * @return mixed
     */
    public function slice($start, $length);
}
