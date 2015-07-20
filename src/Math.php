<?php
namespace OPHP;

interface Math
{
    /**
     * Adds numeric value of every element. Non-numeric elements have a value of 0.
     * @return float|int|number sum of elements
     */
    public function sum();

    /**
     * Muliplies numeric value of every element. Non-numeric elements have a value of 0.
     * @return float|int|number product of elements
     */
    public function product();
}
