# What is this repo for?
This is my second attempt to learn from this book. The [first attempt](https://github.com/Mohammed-Lashein/scandiweb-BE-prototype) was a couple of months ago. The problem with the aforementioned repository is that it had several concerns, and it was cluttered with a lot of noise.

But this repository just focuses on this book's code as a reference for both Design Patterns and TDD.

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
____
### Assignment in the while loop expression
This syntax is used extensively in this chapter's code: 
```php
while($item = $it->next()) {
  // some code...
}
```
I found answers to [this question on stackovervlow](https://stackoverflow.com/questions/6681075/while-loop-in-php-with-assignment-operator) to explain this syntax in a good way.
____
### `AvailableItemsIterator::next()` implementation
  This is my 1st try to implement this method
```php
class AvailableItemsIterator extends VariantIterator {
  public function next(): Lendable|bool|null {
    // array_filter($this->collection, fn($item) => $item->status === 'library');
    if($this->currentItem()->status === 'library') {
      $itemToReturn = $this->currentItem();
      next($this->collection);
      return $itemToReturn;
    } else {
      // move on
      next($this->collection);
      return null;
    }
  }
}
```
But on encountering an item that has a status of `'borrowed'`, the execution of the method stopped and I didn't know why.
After careful thinking, I found that since we were returning null from the method, the while loop will exit and will not complete.
That's why on encountering an item of status `'borrowed'` the execution stopped.

What is the solution then? 🤔
We need to store the available items that we will loop over in a property of `AvailableItemsIterator`: 
```php
class AvailableItemsIterator extends VariantIterator {
  private array $availableItems = [];
  public function __construct(array $collection) {
    parent::__construct($collection);
    $this->availableItems = array_filter($this->collection, fn($item) => $item->status === 'library');
  }
  public function next(): Lendable|bool {
    if($this->isFirstCall) {
      $this->isFirstCall = false;
      return current($this->availableItems);
    }
    return next($this->availableItems);
  }
}
```
____
### Why in `AvailableItemsIterator::next()` test we need to create a new instance of the class in order for the change made to `$this->collection` to get reflected?
```php
  test("LibraryAvailableIterator works correctly", function() {
    $dvd = new Media('test', 2015, 'dvd');
    $this->lib->add($dvd);
    $this->lib->add(new Media('media4', 2016));

    $it = $this->lib->getVariantIterator();
    $output = '';
    while($item = $it->next()) {
      $output .= $item->getName();
    }
    expect($output)->toBe('media1media2media3testmedia4');

    $libAvailableIterator = $this->lib->getAvailableItemsIterator();

    $dvd->checkout('John');

    // why do we need to create another instance?
    $libAvailableIterator2 = $this->lib->getAvailableItemsIterator();

    $output = '';
    while ($item = $libAvailableIterator2->next()) {
      $output .= $item->getName();
    }
    expect($output)->toBe('media1media2media3media4');
  })->only();
```
Chat explained to me:  
Since each iterator gets a snapshot of `$this->collection` on instantiation, any change **is not** reflected to the Iterator, that's why we need to create another iterator to be able to read these reflected changes.

UPDATE: There is no need for the 2nd `$libAvailableIterator` since we can instantiate from `AvailableItemsIterator` when we need to use it. I will keep the code example just for clarity.
