<?php

namespace Chapters\Ch8;
class Media extends Lendable {
  private string $name;
  private int $year;
  private string $type;

  public function __construct($n, $y, $t = 'dvd') {
    $this->name = $n;
    $this->year = (int) $y;
    $this->type = $t;
  }
  public function getName(): string {
    return $this->name;
  }
}