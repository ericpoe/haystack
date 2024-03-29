![GitHub Workflow Status for Code Testing (master branch)](https://img.shields.io/github/workflow/status/ericpoe/haystack/Unit%20Testing/master?label=Unit%20Testing&style=flat-square)
![GitHub Workflow Status for Code Analysis (master branch)](https://img.shields.io/github/workflow/status/ericpoe/haystack/Code%20Analysis/master?label=Code%20Analysis&style=flat-square)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Packagist Version](https://img.shields.io/packagist/v/ericpoe/haystack.svg?style=flat-square)](https://packagist.org/packages/ericpoe/haystack)

# Haystack
Forget Haystack vs Needle order, the object *IS* the Haystack. Haystack is a library that allows for pipelining,
immutable structures, and UTF-8 strings.

## Install
Haystack is installable as a [Composer](http://getcomposer.org) package:

```sh
$ composer require ericpoe/haystack
```

## Running tests

```sh
$ git clone ...
$ composer install
$ vendor/bin/phpunit
```

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

## How do I?
Check out the [manual](docs/manual.md) for all the things you can do with the Haystack library.
