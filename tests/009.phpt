--TEST--
zofpli_inflate() try to allocate all memory with truncated $data
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

// build a predictable string
$string = '';
for($i = 0; $i < 30000; ++$i) $string .= $i . ' ';
var_dump(strlen($string));
// deflate string
$deflated = zopfli_deflate($string, 1);
var_dump(strlen($deflated));
// truncate $deflated string
$truncated = substr($deflated, 0, 40154);
var_dump(strlen($truncated));
// inflate $truncated string (check if it will not eat all memory)
var_dump(zopfli_inflate($truncated));
?>
--EXPECTF--
int(168890)
int(41713)
int(40154)

Warning: %sinflate(): data error in %s on line %d
bool(false)
