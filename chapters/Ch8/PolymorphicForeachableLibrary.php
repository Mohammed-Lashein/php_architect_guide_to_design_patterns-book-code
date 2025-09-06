<?php

namespace Chapters\Ch8;

use Iterator;
class PolymorphicForeachableLibrary extends Library implements Iterator {
    private StandardLibraryIterator $iterator; // This iterator will be on of  our custom iterators
    protected string $iterator_type;
    public function __construct() {
      $this->iteratorType();
    }
  public function current(): mixed {
    return $this->iterator->current();
  }
  public function next(): void {
    $this->iterator->next();
  }
  public function key(): mixed {
    // I think this should be key(current($this->collection))
    /* 
      After using this class, it seems that below is the correct approach.
      Why? Because on each iteration, $this->collection will refer to the CURRENT element in the iteration
      not the actual collection

      Actually, $this->collection will always refer to the array, but instead on using 
      key($this->collection), it will return the key of the element the internal pointer of the array
      is pointing to
    */
    return $this->iterator->key();
  }
  public function valid(): bool {
    return $this->iterator->valid();
  }
  public function iteratorType($type = false) {
    switch(strtolower($type)) {
      case 'released': 
        $this->iterator_type = 'ForeachableReleasedIterator';
        break;
      default:
        $this->iterator_type = 'StandardLibraryIterator';
    }
    $this->rewind();
  }
  public function rewind(): void {
    $this->iterator = new StandardLibraryIterator($this->collection);
    $this->iterator->rewind();
  }
}