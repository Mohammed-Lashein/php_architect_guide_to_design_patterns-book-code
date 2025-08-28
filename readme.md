# Here I document my notes through reading each chapter

## Chapter8: The Iterator pattern
Some php functions to know: 
- `current()`: returns the value of the array element that's currently being pointed to by the array pointer.
- `reset()`: resets the internal pointer of an array to the **first** element and returns the value of the first array element.
- `next()`: Advances the internal pointer of an array to the next element AND return the value of that element
______
### Regarding `Iterator::next()`
It is a bit unusual that this method returns a boolean instead of a `Lendable` instance.
I wanted to follow the same behavior of php function `next()`.

Update: After [reading in the docs](https://www.php.net/manual/en/iterator.next.php), I found that the implementation of Iterator interface specifies that `Iterator::next()` returns `void`.

So the writer was partially correct in that he didn't write the code to return the next element to iterate over.
____
### On implementing the `VariantIterator::next()`
Initially, I thought of this implementation regarding that method: 
```php
  // In VariantIterator 
  public function next(): Lendable|bool {
    // if we haven't reached the end of the collection, return the next el in the collection 
    $collectionIsNotEmptyYet = next($this->collection) !== false;
    if($collectionIsNotEmptyYet) {
      return $this->currentItem();
    }
    // // if we have reached ... , return false
    return next($this->collection);
  }
```
So what's wrong with the above implementation? 🤔
The problem is that we are advancing the internal pointer of `$this->collection` before accessing the 1st element, thus our test fails.

Another implementation: 
```php
// In VariantIterator
/*
 The return value of static::currentItem() should be changed to also allow a bool. The last iteration of static::next() will return it. 
*/
  public function currentItem(): Lendable|bool {
    // return $this->collection[$this->pointer];
    return current($this->collection);
  }
  public function next(): Lendable|bool {
    // a much cleaner one, 
    $currentItem = $this->currentItem();
    next($this->collection);
    return $currentItem;
  }
```
I will follow the writer's implementation (where he used a flag to determine the 1st iteration) to stick with the code in the book.