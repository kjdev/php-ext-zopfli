--TEST--
Test zopfli_png_recompress() function
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

$origin_file = dirname(__FILE__) . '/php.png';
$recompress_file = dirname(__FILE__) . '/php-recomp.png';

$data = file_get_contents($origin_file);
$recompress = zopfli_png_recompress($data);
file_put_contents($recompress_file, $recompress);

echo "*** Testing zopfli_png_recompress() ***\n";
echo  "ORIGIN: ", filesize($origin_file), "\n";
echo  "RECOMPRESS: ", filesize($recompress_file), "\n";
?>
===Done===
--EXPECT--
*** Testing zopfli_png_recompress() ***
ORIGIN: 2413
RECOMPRESS: 2333
===Done===
