<?php

namespace Chapters\Ch8;

$arr = ['one', 'two', 'three'];

// var_dump(reset($arr)); // one
// var_dump(current($arr)); // one
// var_dump(next($arr)); // two
// var_dump(next($arr)); // three

class Iterator {
  private array $collection = [];
  /* 
    In popps book, we used the pointer property, but here in php architect book, the writer uses native 
    methods that are surprisingly not deprecated in php like current(), next() and reset()
  */
  // private int $pointer = 0;
  public function __construct(array $collection) {
    $this->collection = $collection;
  }
  public function isDone() {
    return current($this->collection) === false;
  }
  public function currentItem(): Lendable {
    // return $this->collection[$this->pointer];
    return current($this->collection);
  }
  public function next(): bool {
    return next($this->collection) !== false;
  }
}