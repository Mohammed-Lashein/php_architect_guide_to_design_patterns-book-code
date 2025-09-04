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
    $this->lib->add(new Media('media1', 2010));
    $this->lib->add(new Media('media2', 2002));
    $this->lib->add(new Media('media3', 2007));
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

    expect($it->next())->toBeTrue();
    $nextItem = $it->currentItem();
    expect($nextItem->getName())->toBe("media2");
    expect($it->isDone())->toBeFalse();

    // 3rd iteration... All elements have been looped over
    expect($it->next())->toBeTrue();
    $lastItem = $it->currentItem();
    expect($lastItem->getName())->toBe('media3');

    /*
      Note that you need to call $it->next() for a last time before calling $it->isDone() to make sure
    that $it->isDone() will return the expected value 
    */
    expect($it->next())->toBe(false);
    expect($it->isDone())->toBeTrue();
  });

  test("can be used correctly in a loop", function() {
    $output = '';
    for($it = $this->lib->getIterator(); !$it->isDone(); $it->next()) {
      $output .= $it->currentItem()->getName();
    }
    expect($output)->toBe('media1media2media3');
  });

  /* 
    I skipped trying the implementation of making an internal pointer as the 
  writer made it a bit complex than having a pointer property on Iterator
  */

  // I am adding other iterators test here since the beforeEach() hook is located in this describe block
  test("VariantIterator works correctly", function() {
    $variantIterator = $this->lib->getVariantIterator();
    $output = '';
    while ($item = $variantIterator->next()) {
      $output .= $item->getName();
    }
      expect($output)->toBe('media1media2media3');
  });
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

    $dvd->checkout('John');
    $libAvailableIterator = $this->lib->getAvailableItemsIterator();

    $output = '';
    while ($item = $libAvailableIterator->next()) {
      $output .= $item->getName();
    }
    expect($output)->toBe('media1media2media3media4');
  });
  test("LibraryReleasedIterator works correctly", function() {
    $this->lib->add(new Media('media4', 1999));
    $it = $this->lib->getLibraryReleasedIterator();

    $output = '';
    while($item = $it->next()) {
      // the writer has a better implementation than mine
      // My solution has a problem in that there will be a space after the last element. It is not a big deal
      // as we can rtrim it
      $output .= $item->getName() . '-' . $item->getYear() . ' ';
    }

    expect($output)->toBe('media4-1999 media2-2002 media3-2007 media1-2010 ');
  })->only();
});
