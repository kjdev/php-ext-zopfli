--TEST--
zopfli_compress()/zopfli_uncompress() compatible with gzcompresse()/gzuncompress()
--SKIPIF--
<?php if (!extension_loaded("zlib")) print "skip"; ?>
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

include(dirname(__FILE__) . '/data.inc');

echo "*** Tesging zopfli_compress()/gzuncompress() ***\n";
$packed = zopfli_compress($data);
echo strlen($packed)." ".strlen($data)."\n";
$unpacked = gzuncompress($packed);
if (strcmp($data, $unpacked) == 0) echo "Strings are equal\n";

echo "*** Tesging gzcompress()/zopfli_uncompress() ***\n";
$packed = gzcompress($data);
echo strlen($packed)." ".strlen($data)."\n";
$unpacked = zopfli_uncompress($packed);
if (strcmp($data, $unpacked) == 0) echo "Strings are equal\n";
?>
--EXPECT--
*** Tesging zopfli_compress()/gzuncompress() ***
1750 3547
Strings are equal
*** Tesging gzcompress()/zopfli_uncompress() ***
1794 3547
Strings are equal
