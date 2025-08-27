<?php

namespace Chapters\Ch8;

$arr = ['one', 'two', 'three'];

var_dump(reset($arr)); // one
var_dump(current($arr)); // one
var_dump(next($arr)); // two
var_dump(next($arr)); // three

class Iterator {

}