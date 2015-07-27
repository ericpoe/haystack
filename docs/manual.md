# OPHP Manual

## How To Use
You can start using OPHP right away, like so:

```php
    use OPHP\OArray;
    $myArray = new OArray();
    $myArray = $myArray->insert("orange", "o");
```

Or you can use OPHP later on, like so:

```php
    use OPHP\OArray;
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

### Container Methods

**contains($element)** - Checks to see if $element is contained within the current OString or OArray. Returns boolean.

```php
    $myString = new OString("I am the very model of a modern major-general");
    $myArray = new OArray(["apple", "banana", "celery"]);

    $myString->contains("model"); // bool(true)
    $myString->contains("view"); // bool(false)

    $myArray->contains("banana"); // bool(true)
    $myArray->contains("raspberry"); // bool(false)
```

**locate($element)** - Returns the array key of the first instance of $element within the current OString or OArray. Returns a "-1" if not found.

```php
    $myString = new OString("I am the very model of a modern major-general");
    $myArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);

    $key = $myString->locate("a"); // int(2)
    $key = $myString->locate("mod"); // int(14)
    $key = $myString->locate("z"); // int(-1)

    $key = $myArray->locate("apple"); // "a"
    $key = $myArray->locate("daikon"); // int(-1)
```

**append($element)** - Adds an element to the end of the collection.

```php
    $myString = new OString("I am the very model of a modern major-general");
    $myArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);

    $newString = $myString->append(", I've information vegetable, animal, and mineral");
    // "I am the very model of a modern major-general, I've information vegetable, animal, and mineral"

    $newArray = $myArray->append(["d" => "daikon"]); // ["a" => "apple", "b" => "banana", "c" => "celery", ["d" => "daikon"]]
```

**insert($element, $key = null)** - Inserts an element at the $key location; if $key is not identified, the element is inserted at the end.

```php
    $myString = new OString("I am the very model of a modern major-general");
    $myArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);
    
    $newString = $myString->insert(", I've information vegetable, animal, and mineral");
    // "I am the very model of a modern major-general, I've information vegetable, animal, and mineral"
    $newString = $myString->insert("think that I ", 2);
    // "I think that I am the very model of a modern major-general"
    
    $newArray = $myArray->insert(["d" => "daikon"]); //["a" => "apple", "b" => "banana", "c" => "celery", "d" => "daikon"]
    $newArray = $myArray->insert(["a" => "apricot"]); // ["a" => ["apple", "apricot"], "b" => "banana", "c" => "celery"]
    $newArray = $myArray->insert("apricot", "a"); // ["a" => ["apple", "apricot"], "b" => "banana", "c" => "celery"]
```

**remove($element)** - Removes an element, if found.

```php
    $myString = new OString("I am the very model of a modern major-general");
    $myArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);
    
    $newString = $myString->remove("the very model of "); // "I am a modern major-general"
    $newArray = $myArray->remove("banana"); // ["a" => "apple", "c" => "celery"]
```

**slice($start, $length = null)** - Shows only part of the array or string. 
* **$start** (integer) is the point in the OArray or OString to start slicing. If this number is positive, start that far on the left; if this number is negative, start that far on the right.
* **$length** (integer or null) is the amount of items to slice. If this number is null, the length will be the rest of the OArray or OString; if the length is positive, the length will be the distance forward the OArray or OString will be sliced; if the length is negative, that is the length backwards the OArray or OString will be sliced.
* **Note:** The numeric-key values of the OString and the OArray will be reset; the string-key values of an OArray will not be reset. 

```php
    $myString = new OString("I am the very model of a modern major-general");
    $myListArray = new OArray(["apple", "banana", "celery"]);
    $myDictArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);
    
    $newString = $myString->slice(2); // "am the very model of a modern major-general"
    $newString = $myString->slice(-7); // "general"
    $newString = $myString->slice(2, 2); // "am"
    
    $newArray = $myListArray->slice(1); // ["banana", "celery"]
    $newArray = $myListArray->slice(-2); // ["banana", "celery"]
    $newArray = $myListArray->slice(1, 1); // ["banana"]
    
    $newArray = $myDictArray->slice(1); // ["b" => "banana", "c" => "celery"]
    $newArray = $myDictArray->slice(-2); // ["b" => "banana", "c" => "celery"]
    $newArray = $myDictArray->slice(1, 1); // ["b" => "banana"]
```

### Functional Methods

**map($callable)** - Returns a new OArray or OString that has had all elements run against the callback.

```php
    $myString = new OString("I am the very model of a modern major-general");
    $myArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);
    
    $rot13 = function ($letter) {
        if (" " === $letter || "-" === $letter) { 
            return $letter;
        }
        
        return chr(97 + (ord($letter) - 97 + 13) % 26);
    };
    
    $newString = $myString->map($rot13); // "V nz gur irel zbqry bs n zbqrea znwbe-trareny"
    
    $capitalize = function ($word) {
        return strtoupper($word);
    };
    
    $newArr = $myArray->map($capitalize); // ["a" => "APPLE", "b" => "BANANA", "c" => "CELERY"]
```

**walk($callable)** - Walk does an in-place update of items in the object.
* **Note:** Since the update is in-place, this breaks the immutablity of OPHP objects. This is useful for very large implementations of the OPHP where cloning the object would be memory intensive.

```php
    $myString = new OString("I am the very model of a modern major-general");
    $myArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);
    
    $rot13 = function ($letter, $key) {
        if (" " === $letter || "-" === $letter) {
            return $myString[$key] = $letter;
        }
    
        return $myString[$key] = chr(97 + (ord($letter) - 97 + 13) % 26);
    };
    
    $myString->walk($rot13); // "V nz gur irel zbqry bs n zbqrea znwbe-trareny"
    
    $capitalize = function ($word, $key) {
            return $myArray[$key] = strtoupper($word);
        };
        
    $myArray->map($capitalize); // ["a" => "APPLE", "b" => "BANANA", "c" => "CELERY"]
    
```


**filter($callable = null, $flag = null)** - Iterates over each value in the container passing them to the callback function. If the callback function returns true, the current value from container is returned into the result container. Container keys are preserved.
* **Note:** Default is to filter by value.
* **Flag: USE_KEY** Filters against the OPHP container's key
* **Flag: USE_BOTH** Filters against the OPHP container's value and key. 
    * **Note:** the callback parameter order is `$value` then `$key`

```php
    $myString = new OString("I am the very model of a modern major-general");
    $myArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);
    
    $removeLowerCaseVowels = function ($letter) {
        $vowels = new OString("aeiou");
        return !$vowels->contains($letter);
    };
    
    $consonantWord = $myString->filter($removeLowerCaseVowels); // "I m th vry mdl f  mdrn mjr-gnrl"
    
    $vowel = function ($word) {
        $vowels = new OString("aeiou");
        return $vowels->contains($word[0]);
    };
    
    $firstLetterVowelWords = $myArray->filter($vowel); // ["a" => "apple"]
```

* **USE_BOTH Example**
    
    ```php    
    $myArray = new OArray(["a" => "bobble", "b" => "apple", "c" => "cobble"]);
    
    $vowel_both = function ($value, $key) {
        $vowels = new OString("aeiou");
    
        if ($vowels->contains($value[0])) {
            return true;
        }
    
        return $vowels->contains($key);
    };
        
    $vowelFoods = $myArray->filter($vowel_both, OArray::USE_BOTH); // ["a" => "bobble", "b" => "apple"]
        
    ```
    
**reduce()** - Iteratively reduce the OPHP Collection to a single value using a callback function
  * **$callback:** mixed callback ( mixed $carry , mixed $item )
      *   **$carry:** Holds the return value of the previous iteration; in the case of the first iteration it instead holds the value of initial.
      *   **$item:** Holds the value of the current iteration.
  * **$initial:** If the optional initial is available, it will be used at the beginning of the process, or as a final result in case the array is empty.
  
```php
    $myString = new OString("I am the very model of a modern major-general");
    
    $encode = function ($carry, $item) {
        if (ctype_upper($item) || ctype_lower($item)) {
            $value = (ord($item) % 26) + 97;
            $carry .= chr($value);
        } else {
            $carry .= $item;
        }
        
        return $carry;
    };
    
    $codedMessage = $myString->reduce($encode); // "v tf max oxkr fhwxe hy t fhwxkg ftchk-zxgxkte"
    
    $myArray = new OArray(range(1,10));
    
    $sum = function ($carry, $item) {
        $carry += $item;
        return $carry;
    };
    
    $bigNum = $myArray->reduce($sum); // int(55)
```

**head()** - Returns the first element of the OArray or OString.

```php
    $myString = new OString("I am the very model of a modern major-general");
    $myArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);
    
    $headString = $myString->head(); // "I"
    $headArray = $myArray->head(); // ["a" => "apple"]
    
```

**tail()** - Returns all of the elements that are not the head() of the OArray or OString

```php
    $myString = new OString("I am the very model of a modern major-general");
    $myArray = new OArray(["a" => "apple", "b" => "banana", "c" => "celery"]);
    
    $tailString = $myString->tail(); // " am the very model of a modern major-general"
    $tailArray = $myArray->tail(); // ["b" => "banana", "c" => "celery"]
    
```

### Collection Pipeline

`OArray` can also be used as a concise [Collection
Pipeline](http://martinfowler.com/articles/collection-pipeline/) by
using map, reduce and filter with a fluent interface.

```php
$array = new OArray([3, 5, 7, 9, 11]);
$result = $array->map(function ($i) { return $i * $i; })    // Square [9, 25, 49, 81, 121]
  ->filter(function ($i) { return $i > 30; })               // Only large numbers [49, 81, 121]
  ->reduce(function ($carry, $i) { return $carry += $i; }); // Sum

var_dump($result); // int(251)
```

### Math

**product()** - Calculates the product of the values in the collection. Any non-number values are equal to 0.

```php
    $myString = new OString("1, 2, 3, 4, 5, 6, 7, 8, 9, 10");
    $myArray = new OArray(range(1, 10));
    
    $myString->product(); // int(3628800)
    $myArray->product(); // int(3628800)
```

**sum()** - Calculates the sum of the values in the collection. Any non-number values are equal to 0.

```php
    $myString = new OString("1, 2, 3, 4, 5, 6, 7, 8, 9, 10");
    $myArray = new OArray(range(1, 10));
    
    $myString->sum(); // int(55)
    $myArray->sum(); // int(55)
```

## To install
*this will come later*
