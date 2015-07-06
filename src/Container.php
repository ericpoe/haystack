<?php
namespace OPHP;

interface Container
{
    /**
     * Determines if a $thing is in the current object.
     *
     * @param $thing
     * @return boolean
     */
    public function contains($thing);

    /**
     * Finds the location of $thing in the current object. If it does not exist, the user will be notified
     *
     * @param $thing
     * @return int - array-notation location of $thing in current object; "-1" if not found
     */
    public function locate($thing);

    /**
     * Concatenates two things of the same type.
     *
     * @param $thing
     * @return mixed
     */
    public function append($thing);

    /**
     * Inserts a $thing at a specified location; if no key is provided, $thing will be added to the back.
     *
     * @param          $thing
     * @param int|null $key
     * @return mixed
     */
    public function insert($thing, $key = null);

    /**
     * @param $thing
     * @return mixed
     */
    public function remove($thing);

    /**
     * @param $start
     * @param $length
     * @return mixed
     */
    public function slice($start, $length);
}
