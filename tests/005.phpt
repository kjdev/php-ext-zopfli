--TEST--
zopfli_deflate()/zopfli_inflate() and invalid params
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

var_dump(zopfli_deflate());
var_dump(zopfli_deflate("", -1));
var_dump(zopfli_deflate("", 100));

var_dump(zopfli_deflate(""));
var_dump(zopfli_deflate("", 100));

$string = "Answer me, it can't be so hard
Cry to relieve what's in your heart
Desolation, grief and agony";

var_dump($data1 = zopfli_deflate($string));
var_dump($data2 = zopfli_deflate($string, 100));

var_dump(zopfli_inflate());
var_dump(zopfli_inflate(""));
var_dump(zopfli_inflate("asfwe", 100));
var_dump(zopfli_inflate("asdf", -1));

var_dump(zopfli_inflate("asdf"));
var_dump(zopfli_inflate("asdf", 100));

var_dump(zopfli_inflate($data1));
var_dump(zopfli_inflate($data2));
$data2[4] = 0;
var_dump(zopfli_inflate($data2));

echo "Done\n";
?>
--EXPECTF--
Warning: zopfli_deflate() expects at least 1 parameter, 0 given in %s on line %d
NULL

Warning: zopfli_deflate(): compression iterations (-1) must be greater than 0 in %s on line %d
bool(false)
string(0) ""
string(0) ""
string(0) ""
string(%d) "%a"
string(%d) "%a"

Warning: %sinflate() expects at least 1 parameter, 0 given in %s on line %d
NULL

Warning: %sinflate(): data error in %s on line %d
bool(false)

Warning: %sinflate(): data error in %s on line %d
bool(false)

Warning: %sinflate(): length (-1) must be greater or equal zero in %s on line %d
bool(false)

Warning: %sinflate(): data error in %s on line %d
bool(false)

Warning: %sinflate(): data error in %s on line %d
bool(false)
string(94) "Answer me, it can't be so hard
Cry to relieve what's in your heart
Desolation, grief and agony"
string(94) "Answer me, it can't be so hard
Cry to relieve what's in your heart
Desolation, grief and agony"

Warning: %sinflate(): data error in %s on line %d
bool(false)
Done
