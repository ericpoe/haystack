<?php
namespace OPHP;

interface Container
{
    /**
     * Determines if a $thing is in the current object.
     * @param $thing
     * @return boolean
     */
    public function contains($thing);

    /**
     * Finds the location of $thing in the current object. If it does not exist, the user will be notified
     * @param $thing
     * @return int - array-notation location of $thing in current object; "-1" if not found
     */
    public function locate($thing);
}
