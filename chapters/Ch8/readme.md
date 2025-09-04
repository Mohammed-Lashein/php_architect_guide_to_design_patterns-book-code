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