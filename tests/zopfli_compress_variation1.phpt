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
string(32) "6e274453c23959add19291e8769753b7"
string(32) "1a06702b6540d2f6d0b4dcb06fd02205"
===Done===
