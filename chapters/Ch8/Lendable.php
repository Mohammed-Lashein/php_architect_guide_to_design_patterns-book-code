<?php


namespace Chapters\Ch8;

class Lendable {
  public string $borrower = '';
  public ?string $status = 'library';
  public function checkout(string $name): void {
    $this->borrower = $name;
    $this->status = 'borrowed';
  }
  public function checkin() {
    $this->status = 'library';
    $this->borrower = false;
  }
}