<?php
require 'src/SharedMemory.php';
require 'src/Storage.php';

$store = new Storage(1048576);
$store[0] = 0;
$store[1] = 1;
$store[2] = 2;
$store[3] = 3;
$store[4] = 4;
$store[5] = 5;
$store[6] = 10;
//unset($store[0]);
//$store->destroy();
var_dump($store->search(0));
//var_dump($store);