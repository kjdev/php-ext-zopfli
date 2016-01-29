--TEST--
zopfli_deflate()/zopfli_inflate() compatible with gzdeflate()/gzinflate()
--SKIPIF--
<?php if (!extension_loaded("zlib")) print "skip"; ?>
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

include(dirname(__FILE__) . '/data.inc');

echo "*** Tesging zopfli_deflate()/gzinflate() ***\n";
$packed = zopfli_deflate($data);
echo strlen($packed)." ".strlen($data)."\n";
$unpacked = gzinflate($packed);
if (strcmp($data, $unpacked) == 0) echo "Strings are equal\n";

echo "*** Tesging gzdeflate()/zopfli_inflate() ***\n";
$packed = gzdeflate($data);
echo strlen($packed)." ".strlen($data)."\n";
$unpacked = zopfli_inflate($packed);
if (strcmp($data, $unpacked) == 0) echo "Strings are equal\n";
?>
--EXPECT--
*** Tesging zopfli_deflate()/gzinflate() ***
1746 3547
Strings are equal
*** Tesging gzdeflate()/zopfli_inflate() ***
1788 3547
Strings are equal
