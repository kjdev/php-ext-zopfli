--TEST--
Test zofpli_uncompress() function : basic functionality
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

include(dirname(__FILE__) . '/data.inc');

echo "*** Testing zopfli_uncompress() : basic functionality ***\n";


// Initialise all required variables
$compressed = zopfli_compress($data);

echo "\n-- Basic decompress --\n";
var_dump(strcmp($data, zopfli_uncompress($compressed)));


$length = 3547;
echo "\n-- Calling zopfli_uncompress() with max length of $length --\n";
echo "Result length is ".  strlen(zopfli_uncompress($compressed, $length)) .  "\n";

?>
===DONE===
--EXPECT--
*** Testing zopfli_uncompress() : basic functionality ***

-- Basic decompress --
int(0)

-- Calling zopfli_uncompress() with max length of 3547 --
Result length is 3547
===DONE===