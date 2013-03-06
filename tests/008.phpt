--TEST--
encoders fail on widely varying binary data
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

// test 50 bytes to 50k
$b = array(
    50,
    500,
    5000,
    50000,
//  1000000, // works, but test would take too long
);

$s = '';
$i = 0;

foreach ($b as $size) {
    do {
        $s .= chr(rand(0,255));
    } while (++$i < $size);
    var_dump($s === zopfli_inflate(zopfli_deflate($s)));
    var_dump($s === zopfli_uncompress(zopfli_compress($s)));
    var_dump($s === zopfli_inflate(substr(zopfli_encode($s), 10, -8)));
}
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
