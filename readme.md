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