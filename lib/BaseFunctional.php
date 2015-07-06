<?php
namespace OPHP;

interface BaseFunctional
{
    /**
     * Applies the callback to the elements of the given array
     *
     * @param callable $func
     * @return mixed
     */
    public function map(callable $func);

    /**
     * Walk does an in-place update of items in the object.
     *
     * Since the update is in-place, this breaks the immutablity of OPHP objects. This is useful for very large
     * implementation of the OPHP where cloning the object would be memory intensive.
     *
     * @param callable $func
     * @return bool
     */
    public function walk(callable $func);

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
     * @return mixed
     *
     * @throws \ErrorException
     */
    public function filter(callable $func = null, $flag = null);

    /**
     * Shows the first element of the collection
     *
     * @return mixed
     */
    public function head();

    /**
     * Shows the collection that doesn't include the head
     *
     * @return mixed
     */
    public function tail();
}
