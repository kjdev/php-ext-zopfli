--TEST--
zofpli_inflate() and $length argument
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

$original = 'aaaaaaaaaaaaaaa';
$packed = zopfli_deflate($original);
echo strlen($packed)." ".strlen($original)."\n";
$unpacked = zopfli_inflate($packed, strlen($original));
if (strcmp($original,$unpacked) == 0) echo "Strings are equal\n";

$unpacked = zopfli_inflate($packed, strlen($original)*10);
if (strcmp($original,$unpacked) == 0) echo "Strings are equal\n";

$unpacked = zopfli_inflate($packed, 1);
if ($unpacked === false) echo "Failed (as expected)\n";
?>
--EXPECTF--
4 15
Strings are equal
Strings are equal

Warning: %sinflate(): insufficient memory in %s on line %d
Failed (as expected)
