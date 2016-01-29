--TEST--
Test zofpli_compress() function : variation
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

include(dirname(__FILE__) . '/data.inc');

echo "*** Testing zopfli_compress() : variation ***\n";

echo "\n-- Testing multiple compression --\n";
$output = zopfli_compress($data);
var_dump( md5($output));
var_dump(md5(zopfli_compress($output)));

?>
===Done===
--EXPECTF--
*** Testing zopfli_compress() : variation ***

-- Testing multiple compression --
string(32) "b2000ecc797222480a58ec6c2d6ae605"
string(32) "714d6a63fa4992240adfcca023b0ad6c"
===Done===
