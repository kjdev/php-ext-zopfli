--TEST--
zopfli_encode()/zopfli_dencode() compatible with gzencode()/gzdencode()
--SKIPIF--
<?php
if (!extension_loaded("zlib")) print "skip";
if (!function_exists("gzdecode")) print "skip";
?>
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

include(dirname(__FILE__) . '/data.inc');

echo "*** Tesging zopfli_encode()/gzdecode() ***\n";
$packed = zopfli_encode($data);
echo strlen($packed)." ".strlen($data)."\n";
$unpacked = gzdecode($packed);
if (strcmp($data, $unpacked) == 0) echo "Strings are equal\n";

echo "*** Tesging gzencode()/zopfli_decode() ***\n";
$packed = gzencode($data);
echo strlen($packed)." ".strlen($data)."\n";
$unpacked = zopfli_decode($packed);
if (strcmp($data, $unpacked) == 0) echo "Strings are equal\n";

echo "*** Tesging zopfli_encode()/gzuncompress() ***\n";
$packed = zopfli_encode($data, 15, ZOPFLI_ZLIB);
echo strlen($packed)." ".strlen($data)."\n";
$unpacked = gzuncompress($packed);
if (strcmp($data, $unpacked) == 0) echo "Strings are equal\n";

echo "*** Tesging zopfli_encode()/gzdeflate() ***\n";
$packed = zopfli_encode($data, 15, ZOPFLI_DEFLATE);
echo strlen($packed)." ".strlen($data)."\n";
$unpacked = gzinflate($packed);
if (strcmp($data, $unpacked) == 0) echo "Strings are equal\n";

echo "*** Tesging zopfli_encode(): ZOPFLI_ZLIB ***\n";
$packed = zopfli_encode($data, 15, ZOPFLI_ZLIB);
echo strlen($packed)." ".strlen($data)."\n";
if (strcmp($data, gzuncompress($packed)) == 0) echo "Strings are equal: gzuncompress\n";
if (strcmp($data, zopfli_uncompress($packed)) == 0) echo "Strings are equal: zopfli_uncompress\n";
if (strcmp($data, zopfli_decode($packed, strlen($data), ZOPFLI_ZLIB)) == 0) echo "Strings are equal: zopfli_decode(ZOPFLI_ZLIB)\n";

echo "*** Tesging zopfli_encode(): ZOPFLI_DEFLATE ***\n";
$packed = zopfli_encode($data, 15, ZOPFLI_DEFLATE);
echo strlen($packed)." ".strlen($data)."\n";
if (strcmp($data,gzinflate($packed)) == 0) echo "Strings are equal: gzinflate\n";
if (strcmp($data,zopfli_inflate($packed)) == 0) echo "Strings are equal: zopfli_inflate\n";
if (strcmp($data,zopfli_decode($packed, strlen($data), ZOPFLI_DEFLATE)) == 0) echo "Strings are equal: zopfli_decode(ZOPFLI_DEFLATE)\n";

echo "*** Tesging zopfli_encode(): ZOPFLI_GZIP ***\n";
$packed = zopfli_encode($data, 15, ZOPFLI_GZIP);
echo strlen($packed)." ".strlen($data)."\n";
if (strcmp($data,gzdecode($packed)) == 0) echo "Strings are equal: gzdecode\n";
if (strcmp($data,zopfli_decode($packed)) == 0) echo "Strings are equal: zopfli_decode\n";
if (strcmp($data,zopfli_decode($packed, strlen($data), ZOPFLI_GZIP)) == 0) echo "Strings are equal: zopfli_decode(ZOPFLI_GZIP) equal\n";
?>
--EXPECT--
*** Tesging zopfli_encode()/gzdecode() ***
1764 3547
Strings are equal
*** Tesging gzencode()/zopfli_decode() ***
1806 3547
Strings are equal
*** Tesging zopfli_encode()/gzuncompress() ***
1752 3547
Strings are equal
*** Tesging zopfli_encode()/gzdeflate() ***
1746 3547
Strings are equal
*** Tesging zopfli_encode(): ZOPFLI_ZLIB ***
1752 3547
Strings are equal: gzuncompress
Strings are equal: zopfli_uncompress
Strings are equal: zopfli_decode(ZOPFLI_ZLIB)
*** Tesging zopfli_encode(): ZOPFLI_DEFLATE ***
1746 3547
Strings are equal: gzinflate
Strings are equal: zopfli_inflate
Strings are equal: zopfli_decode(ZOPFLI_DEFLATE)
*** Tesging zopfli_encode(): ZOPFLI_GZIP ***
1764 3547
Strings are equal: gzdecode
Strings are equal: zopfli_decode
Strings are equal: zopfli_decode(ZOPFLI_GZIP) equal
