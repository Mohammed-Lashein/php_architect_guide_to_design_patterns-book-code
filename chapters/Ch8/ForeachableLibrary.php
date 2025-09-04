<?php

namespace Chapters\Ch8;

use Iterator;
class ForeachableLibrary extends Library implements Iterator {
  private bool $valid;
  public function current(): mixed {
    return current($this->collection);
  }
  public function next(): void {
    $this->valid = next($this->collection) !== false;
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
    return key($this->collection);
  }
  public function valid(): bool {
    return $this->valid;
  }
  public function rewind(): void {
    $this->valid = reset($this->collection) !== false;
    reset($this->collection);
  }
}