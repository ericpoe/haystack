<?php
namespace Haystack\Functional;

interface FunctionalInterface
{
    /**
     * Applies the callback to the elements of the given array
     *
     * @param callable $func
     * @param array $containers - a variadic array
     * @return mixed
     */
    public function map(callable $func);

    /**
     * Walk does an in-place update of items in the object.
     *
     * Since the update is in-place, this breaks the immutability of Haystack objects. This is useful for very large
     * implementation of the Haystack where cloning the object would be memory intensive.
     *
     * @param callable $func
     * @return null
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
     * Iteratively reduce the Haystack Collection to a single value using a callback function
     * * $callback: mixed callback ( mixed $carry , mixed $item )
     *   * $carry: Holds the return value of the previous iteration; in the case of the first iteration it instead holds the value of initial.
     *   * $item: Holds the value of the current iteration.
     * * $initial: If the optional initial is available, it will be used at the beginning of the process, or as a final result in case the array is empty.
     *
     * @param callable $func
     * @param null     $initial
     * @return mixed
     */
    public function reduce(callable $func, $initial = null);

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
