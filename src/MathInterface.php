<?php
namespace OPHP;

interface MathInterface
{
    /**
     * Adds numeric value of every element. Non-numeric elements have a value of 0.
     *
     * @return float|int|number sum of elements
     */
    public function sum();

    /**
     * Multiplies numeric value of every element. Non-numeric elements have a value of 0.
     *
     * @return float|int|number product of elements
     */
    public function product();
}
