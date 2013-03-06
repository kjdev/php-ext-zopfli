--TEST--
Test zofpli_deflate() function : variation
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

include(dirname(__FILE__) . '/data.inc');

echo "*** Testing zopfli_deflate() : variation ***\n";



echo "\n-- Testing multiple compression --\n";
$output = zopfli_deflate($data);
var_dump( md5($output));
var_dump(md5(zopfli_deflate($output)));

?>
===Done===
--EXPECT--
*** Testing zopfli_deflate() : variation ***

-- Testing multiple compression --
string(32) "cb531d26850282df19b49ccc1767ae0d"
string(32) "2ffb3664c82c897fb4b48c5bbabdd047"
===Done===
