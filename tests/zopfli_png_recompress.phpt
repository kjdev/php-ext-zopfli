--TEST--
Test zopfli_png_recompress() function
--SKIPIF--
<?php
if (!extension_loaded("zopfli")) {
    die('skip The zopfli extension is not loaded');
}
if (!function_exists("zopfli_png_recompress")) {
    die('skip The zopfli extension is built without zlib support');
}
?>
--FILE--
<?php
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
RECOMPRESS: 2334
===Done===
