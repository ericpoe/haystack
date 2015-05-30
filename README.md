# OPHP
An imagining of PHP as if it were designed around objects.

## Running tests

    $ git clone ...
    $ composer install
    $ vendor/bin/phpunit

## Background
Trying to remember commonly-used PHP functions for strings and arrays in PHP is hard. Are array functions
haystack-needle and string functions needle-haystack, or vice versa? Quick, without looking at documentation or using
a decent IDE, which is correct: `in_array($needle, $haystack)` or `in_array($haystack, needle)`?

Also, even though many of the same kinds of functions are run against arrays and strings, the function names are
wildly different. `strstr` and `in_array` do similar things, yet have different names and are called in
different manners.

## Goal
This project will attempt to match as many string and array verbs as possible. For example, `$foo->contains("elvis")`
should determine if the string "elvis" is contained in the $foo object, it shouldn't matter if `$foo` is a string or
an array. Ditto `$foo->map($callable)` and `$foo->filter($callable)`.

Eventually, I'd like to experiment with treating other basic constructs as objects.

## Status
This project is in its early stages. Use at your own peril. _Caveat Codor_ & _Caveat Emptor_ As a matter of fact, if
you can think of a caveat, follow it!
