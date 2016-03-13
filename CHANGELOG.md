# Change Log

## [1.x.x](https://github.com/ericpoe/haystack/tree/v1.x.x) - 2016-03-xx

### Changes
* Bugfix: HArray::remove() no longer makes the entire array have numeric keys if the removed key was numeric
* Potential BC Break: HArray can now contain objects. So `new HArray(new \DateTime())` is now possible!
* Clean up HString methods

* **Manual**
    * Some examples were changed to show an alternative manner of declaring the Haystack object for use in pipelining (thanks for the heads-up, ajmichels!)

## [1.0.2](https://github.com/ericpoe/haystack/tree/v1.0.2) - 2016-03-01

### Changes

* Bugfix: internal storage when generating an HArray from an HString
* Bugfix: Extended HArray and HString classes can also be pipelined

## [1.0.1](https://github.com/ericpoe/haystack/tree/v1.0.1) - 2016-02-16

### Changes

* Minor performance boost to HString::contains

## [1.0.0](https://github.com/ericpoe/haystack/tree/v1.0.0) - 2016-02-15
Rebranding as Haystack.

### Changes

* OArray is now known as HArray
* OString is now known as HString
* Bugfix dealing with static function declaration
* Manual is now linked from the main README document

## [0.3.0](https://github.com/ericpoe/haystack/tree/v0.3.0) - 2016-02-09

* Drop support for PHP 5.4 and PHP 5.5
* Reduce duplication in code
* Add tests for bug fixes

## [0.2.0](https://github.com/ericpoe/haystack/tree/v0.2.0) - 2015-07-29
OArray <--> String conversion

* **Manual**
    * Move Pipelining section to be closer to the top since it's a good example of what can be done
    * Add OArray & OString- specific sections

### Changes

* OArray now has toOString() method. This is an alias to `implode`
* OString now has toOArray() method. This is an alias to `explode`

## [0.1.1](https://github.com/ericpoe/haystack/tree/v0.1.1) - 2015-07-27
Documentation updates

### Changes

* Spacing requirements are now the same for all files
    * 4-space tabs
    * No empty space at the end of a line

* **Manual**
    * Now includes the required use-statements before each example for using this library
    * Remove installation instructions since these are repeated in the README
    * Add example of Collection Pipeline

* **README**
    * Add copy/paste one liner to require the library

## [0.1.0](https://github.com/ericpoe/haystack/tree/v0.1.0) - 2015-07-26
Initial release

### Contains
**Container methods:**

* append
* contains
* insert
* remove
* slice

**Functional methods:**

* filter
* head
* map
* reduce
* tail
* walk

**Math methods:**

* product
* sum
