--TEST--
zopfli_compress()/zopfli_uncompress() and invalid params
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

var_dump(zopfli_compress());
var_dump(zopfli_compress("", -1));
var_dump(zopfli_compress("", 100));

var_dump(zopfli_compress(""));
var_dump(zopfli_compress("", 9));

$string = "Answer me, it can't be so hard
Cry to relieve what's in your heart
Desolation, grief and agony";

var_dump($data1 = zopfli_compress($string));
var_dump($data2 = zopfli_compress($string, 9));

var_dump(zopfli_uncompress());
var_dump(zopfli_uncompress("", 100));
var_dump(zopfli_uncompress("", -1));

var_dump(zopfli_uncompress(""));
var_dump(zopfli_uncompress("", 9));

var_dump(zopfli_uncompress($data1));
var_dump(zopfli_uncompress($data2));
$data2[4] = 0;
var_dump(zopfli_uncompress($data2));

echo "Done\n";
?>
--EXPECTF--
Warning: zopfli_compress() expects at least 1 parameter, 0 given in %s on line %d
NULL

Warning: zopfli_compress(): compression iterations (-1) must be greater than 0 in %s on line %d
bool(false)
string(%d) "%a"
string(%d) "%a"
string(%d) "%a"
string(%d) "%a"
string(%d) "%a"

Warning: %suncompress() expects at least 1 parameter, 0 given in %s on line %d
NULL

Warning: %suncompress(): %s error in %s on line %d
bool(false)

Warning: %suncompress(): length (-1) must be greater or equal zero in %s on line %d
bool(false)

Warning: %suncompress(): %s error in %s on line %d
bool(false)

Warning: %suncompress(): %s error in %s on line %d
bool(false)
string(94) "Answer me, it can't be so hard
Cry to relieve what's in your heart
Desolation, grief and agony"
string(94) "Answer me, it can't be so hard
Cry to relieve what's in your heart
Desolation, grief and agony"

Warning: %suncompress(): %s error in %s on line %d
bool(false)
Done
