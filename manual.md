# OPHP Manual

## How To Use
You can start using OPHP right away, like so:

``` php
$myArray = new OArray();
$myArray = $myArray->insert("orange", "o");
```

Or you can use OPHP later on, like so:

``` php
$existingArray = range(1, 10);
...
$myArray = new OArray($existingArray);
$myArray = $myArray->insert("orange", "o");
```

## Requirements
* PHP >= 5.4
* [composer](http://getcomposer.org)

## Main Classes of OPHP
The main classes of OPHP are `OArray` and `OString`

`OArray` is designed as a replacement for the standard PHP Array; `OString` is designed as a replacement for the standard PHP String. These two types of objects share many of the same method calls. In OPHP, accessing and manipulating an array, for the most part, is no different from accessing and manipulating a string. Where differences exist, this document shall show those differences.

## Common Methods in OArray and OString

**contains($thing)** - Checks to see if $thing is contained within the current OString or OArray. Returns boolean.

``` php
$myString = new OString("I am the very model of a modern major-general");
$myArray = new OArray(["apple", "banana", "celery"]);

$myString->contains("model"); // true
$myString->contains("view"); // false

$myArray->contains("banana"); // true
$myArray->contains("raspberry"); //false
```

**locate($thing)** - Returns the array key of the first instance of $thing within the current OString or OArray. Returns a "-1" if not found.

``` php
$myString = new OString("I am the very model of a modern major-general");
$myArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);

$key = $myString->locate("a"); // (int) 2
$key = $myString->locate("mod"); // (int) 14
$key = $myString->locate("z"); // (int) -1

$key = $myArray->locate("apple"); // "a"
$key = $myArray->locate("daikon"); // (int) -1
```

**append($thing)** - Adds an element to the end of the collection.

``` php
$myString = new OString("I am the very model of a modern major-general");
$myArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);

$newString = $myString->append(", I've information vegetable, animal, and mineral");
// "I am the very model of a modern major-general, I've information vegetable, animal, and mineral"

$newArray = $myArray->append(["d" => "daikon"]); // ["a" => "apple", "b" => "banana", "c" => "celery", ["d" => "daikon"]]
```

**insert($thing)** - Explanation

**remove($thing)** - Explanation

**slice($thing)** - Explanation

**map($callable)** - Explanation

**walk($callable)** - Explanation

**filter($callable** = null, $flag = null) - Explanation

**head()** - Explanation

**tail()** - Explanation

## To install
*this will come later*
