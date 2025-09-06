# Notes about iterators

## LibraryReleasedIterator
The iterator works, but I don't like our implementation of sorting the `collection`.
We are using `usort`, which sorts the array in place.
But following immutability principles, we should create a copy of the array and sort it instead.

So, I asked claude for some help and here is a good suggestion: 
> store a copy of the `$this->collection`, then apply `usort` to it
```php
$this->storeItems = $this->collection;
```

But wait a minute. In js, arrays are passed by reference by default, so we are still breaking immutability like so.   
**This is not the case in php**. Quoting [from the docs](https://www.php.net/manual/en/language.types.array.php), (Example #31 Array Copying): 
> Array assignment always involves value copying. Use the reference operator `&` to copy an array by reference.

So in php, the default is value copying unlike js.
____
## Classes thoughts
After finishing the chapter, it seems that we have a LOT of classes and the code became more complex. So, it is important to list each class responsibilities for easier maintenance: 

1. `ForeachableLibrary`: Instead of directly calling the iterator in a `while` loop as we did at the beginning of the chapter, we will be able to directly loop over the library in a `foreach` loop.  
How will we do that ? 🤔  
Our `ForeachableLibrary` *implements* `Iterator` interface, that's why we will be able to loop over it directly in the `foreach` loop.

1. `PolymorphicForeachableLibrary`: Allows us to define what iterator type we want to use.  
How are we able to loop over `$this->lib` instance directly in the `foreach` loop? 🤔  
Because `PolymorphicForeachableLibrary` also *implements* `Iterator` interface

1. `StandardLibraryIterator`: It has the methods required by the `Iterator` interface.
But why doesn't `StandardLibraryIterator` implement `Iterator` interface explicitly in the code?  
Because we want to be able to loop over `$this->lib` instead of getting the iterator from it and looping through the iterator in the `foreach` loop (Although there is a better solution to be explained later)