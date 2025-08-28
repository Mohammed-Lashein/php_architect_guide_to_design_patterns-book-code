<?php

use Chapters\Ch8\Iterator;
use Chapters\Ch8\Lendable;
use Chapters\Ch8\Library;
use Chapters\Ch8\Media;

test("checkout status", function() {

  $item = new Lendable();
  // $this->assertFalse($item->borrower);
  expect($item->borrower)->toBe('');

  $item->checkout("John");
  expect($item->status)->toBe("borrowed");
  expect($item->borrower)->toBe("John");
});
test("checkin", function() {
  $item = new Lendable();
  $item->checkout('Johney');
  $item->checkin();
  expect($item->status)->toBe("library");
});
describe("library", function() {
  it("is empty initially", function() {
    $lib = new Library();
    expect($lib->count())->toBe(0);
  });
  test("items count increments on item addition", function() {
    $lib = new Library();
    $item = new Media('media1', 2012);
    $lib->add($item);

    expect($lib->count())->toBe(1);
  });
});
describe("Iterator", function() {
  beforeEach(function() {
    /**
     * @property Library $lib
     */
    $this->lib = new Library();
    $this->lib->add(new Media('media1', 2012));
    $this->lib->add(new Media('media2', 2013));
    $this->lib->add(new Media('media3', 2014));
  });

  test("returned from Library::getIterator()", function() {
    expect($this->lib->getIterator())->toBeInstanceOf(Iterator::class);
  });
  test("isDone() is false initially", function() {
    expect($this->lib->getIterator()->isDone())->toBeFalse();
  });
  test("traverses a collection correctly", function() {
    $it = $this->lib->getIterator();
    $currentItem = $it->currentItem();
    expect($currentItem->getName())->toBe('media1');
    expect($it->isDone())->toBeFalse();

    $nextItem = $it->next();
    expect($nextItem->getName())->toBe("media2");
    expect($it->isDone())->toBeFalse();
  });
});