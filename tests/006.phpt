--TEST--
zopfli_encode() and invalid params
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

var_dump(zopfli_encode());
var_dump(zopfli_encode(1,1,1,1));
var_dump(zopfli_encode("", -10));
var_dump(zopfli_encode("", 1, 100));

var_dump(zopfli_encode("", 15, ZOPFLI_GZIP));
var_dump(zopfli_encode("", 15, ZOPFLI_DEFLATE));

$string = "Light of my sun
Light in this temple
Light in my truth
Lies in the darkness";

var_dump(zopfli_encode($string, 15, 3));

var_dump(zopfli_encode($string, 15, ZOPFLI_GZIP));
var_dump(zopfli_encode($string, 15, ZOPFLI_DEFLATE));

echo "Done\n";
?>
--EXPECTF--
Warning: zopfli_encode() expects at least 1 parameter, 0 given in %s on line %d
NULL

Warning: zopfli_encode() expects at most 3 parameters, 4 given in %s on line %d
NULL

Warning: zopfli_encode(): compression iterations (-10) must be greater than 0 in %s on line %d
bool(false)

Warning: zopfli_encode(): type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE in %s on line %d
bool(false)
string(%d) "%s"
string(0) ""

Warning: zopfli_encode(): type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE in %s on line %d
bool(false)
string(%d) "%s"
string(%d) "%s"
Done
