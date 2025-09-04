<?php

namespace Chapters\Ch8;

class LibraryReleasedIterator extends VariantIterator {
  private array $sortedItems = [];
  public function __construct(array $collection) {
    parent::__construct($collection); // sets the value of $this->collection
    // somewhow sort the collection 
    usort($this->collection, fn($a, $b) => $a->getYear() - $b->getYear());
  }

  public function next(): Lendable|bool {
    if($this->isFirstCall) {
      $this->isFirstCall = false;
      return current($this->collection);
    }
    return next($this->collection);
  }
}