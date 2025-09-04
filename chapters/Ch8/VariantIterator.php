<?php

namespace Chapters\Ch8;

$arr = ['one', 'two', 'three'];

// var_dump(reset($arr)); // one
// var_dump(current($arr)); // one
// var_dump(next($arr)); // two
// var_dump(next($arr)); // three

class VariantIterator {
  protected array $collection = [];
  protected bool $isFirstCall = true;
  /* 
    In popps book, we used the pointer property, but here in php architect book, the writer uses native 
    methods that are surprisingly not deprecated in php like current(), next() and reset()
  */
  // private int $pointer = 0;
  public function __construct(array $collection) {
    $this->collection = $collection;
  }
  public function isDone(): bool {
    return current($this->collection) === false;
  }
  public function currentItem(): Lendable {
    // return $this->collection[$this->pointer];
    return current($this->collection);
  }
  public function next(): Lendable|bool {
    if($this->isFirstCall) {
      $this->isFirstCall = false;
      return $this->currentItem();
    }
    return next($this->collection);
  }
}