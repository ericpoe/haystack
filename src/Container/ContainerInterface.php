<?php
namespace OPHP\Container;

interface ContainerInterface
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
     * Removes the first instance of the supplied value
     *
     * @param $value
     * @return mixed
     */
    public function remove($value);

    /**
     * Shows only part of the array or string.
     *
     * @param int $start - the point in the OArray or OString to start slicing. If this number is positive, start that far on the left; if this number is negative, start that far on the right
     * @param int|null $length - the amount of items to slice. If this number is null, the length will be the rest of the OArray or OString; if the length is positive, the length will be the distance forward the OArray or OString will be sliced; if the length is negative, that is the length backwards the OArray or OString will be sliced
     * @return mixed
     */
    public function slice($start, $length);
}
