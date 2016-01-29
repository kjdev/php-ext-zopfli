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
string(32) "44a09b1d30aaa109b3acb3cd24b93421"
string(32) "673f78d6b4414bb62c6e86add1bcfbfd"
===Done===
