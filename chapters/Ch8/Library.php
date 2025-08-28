<?php

namespace Chapters\Ch8;
class Library {
  private array $collection = [];
  public function count() {
    return count($this->collection);
  }
  public function add(Lendable $item): void {
    $this->collection[] = $item;
  }
  public function getIterator(): Iterator {
    return new Iterator($this->collection);
  }
  public function getVariantIterator(): VariantIterator {
    return new VariantIterator($this->collection);
  }
  public function getAvailableItemsIterator() {
    return new AvailableItemsIterator($this->collection);
  }
}