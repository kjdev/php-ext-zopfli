--TEST--
Test zofpli_encode() function : variation - verify header contents with all encoding modes
--SKIPIF--
<?php
if( substr(PHP_OS, 0, 3) == "WIN" ) {
  die("skip.. Do not run on Windows");
}
?> 
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

echo "*** Testing zopfli_encode() : variation ***\n";

$data = "A small string to encode\n";

echo "\n-- Testing with each encoding_mode  --\n";
var_dump(bin2hex(zopfli_encode($data, 1)));
var_dump(bin2hex(zopfli_encode($data, 1, ZOPFLI_GZIP)));
var_dump(bin2hex(zopfli_encode($data, 1, ZOPFLI_DEFLATE)));

?>
===DONE===
--EXPECTF--
*** Testing zopfli_encode() : variation ***

-- Testing with each encoding_mode  --
string(90) "1f8b0800000000000203735428ce4dccc951282e29cacc4b5728c95748cd4bce4f49e50200d7739de519000000"
string(90) "1f8b0800000000000203735428ce4dccc951282e29cacc4b5728c95748cd4bce4f49e50200d7739de519000000"
string(54) "735428ce4dccc951282e29cacc4b5728c95748cd4bce4f49e50200"
===DONE===
