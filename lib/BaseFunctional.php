<?php
namespace OPHP;

interface BaseFunctional
{
    public function map(callable $func);

    /**
     * Walk does an in-place update of items in the object.
     *
     * Since the update is in-place, this breaks the immutablity of OPHP objects. This is useful for very large implementations
     * of the OPHP where cloning the object would be memory intensive.
     *
     * @param callable $func
     * @return bool
     */
    public function walk(callable $func);
}
