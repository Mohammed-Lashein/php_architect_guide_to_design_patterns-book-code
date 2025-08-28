<?php

namespace Chapters\Ch8;

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