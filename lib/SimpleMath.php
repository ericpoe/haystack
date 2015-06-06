<?php
namespace OPHP;

interface SimpleMath
{
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
     * @param      $thing
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
     *
     * @param $start
     * @param $length
     * @return mixed
     */
    public function slice($start, $length);

}
