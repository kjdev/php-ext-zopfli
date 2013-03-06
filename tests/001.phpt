--TEST--
zopfli_deflate()/zopfli_inflate()
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

$original = str_repeat("hallo php",4096);
$packed = zopfli_deflate($original);
echo strlen($packed)." ".strlen($original)."\n";
$unpacked = zopfli_inflate($packed);
if (strcmp($original,$unpacked) == 0) echo "Strings are equal\n";

/* with explicit compression level, length */
$original = str_repeat("hallo php",4096);
$packed = zopfli_deflate($original, 100);
echo strlen($packed)." ".strlen($original)."\n";
$unpacked = zopfli_inflate($packed, 40000);
if (strcmp($original,$unpacked) == 0) echo "Strings are equal\n";

$original = 'aaaaaaaaaaaaaaa';
$packed = zopfli_deflate($original);
echo strlen($packed)." ".strlen($original)."\n";
$unpacked = zopfli_inflate($packed);
if (strcmp($original,$unpacked) == 0) echo "Strings are equal";
?>
--EXPECT--
100 36864
Strings are equal
100 36864
Strings are equal
4 15
Strings are equal