--TEST--
zopfli_inflate needs the terminating null byte
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

$array = array(
    'region_id' => 1,
    'discipline' => 23,
    'degrees' => array(),
    'country_id' => 27
);

$serialized = serialize($array);

$deflated = zopfli_deflate($serialized, 1000);
$inflated = zopfli_inflate($deflated);

echo strlen($inflated),"\n";
?>
Done
--EXPECT--
92
Done
