--TEST--
zopfli_compress()/zopfli_uncompress()
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

$original = str_repeat("hallo php",4096);
$packed = zopfli_compress($original);
echo strlen($packed)." ".strlen($original)."\n";
$unpacked = zopfli_uncompress($packed);
if (strcmp($original,$unpacked) == 0) echo "Strings are equal\n";

/* with explicit compression level, length */
$original = str_repeat("hallo php",4096);
$packed = zopfli_compress($original, 100);
echo strlen($packed)." ".strlen($original)."\n";
$unpacked = zopfli_uncompress($packed, 40000);
if (strcmp($original,$unpacked) == 0) echo "Strings are equal\n";
?>
--EXPECT--
105 36864
Strings are equal
105 36864
Strings are equal
