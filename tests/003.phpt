--TEST--
zopfli_encode()
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

$original = str_repeat("hallo php",4096);
$packed = zopfli_encode($original);
echo strlen($packed)." ".strlen($original). "\n";
if (strcmp($original, zopfli_decode($packed)) == 0) echo "Strings are equal";
?>
--EXPECT--
117 36864
Strings are equal
