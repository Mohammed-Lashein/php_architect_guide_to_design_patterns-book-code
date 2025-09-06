<?php

namespace Chapters\Ch8;

class ForeachableReleasedIterator extends StandardLibraryIterator {
  public function __construct(array $collection) {
    parent::__construct($collection); // sets the value of $this->collection
    usort($this->collection, fn($a, $b) => $a->getYear() - $b->getYear());
  }
}