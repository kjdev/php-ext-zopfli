--TEST--
Test zofpli_uncompress() function : error conditions
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

echo "*** Testing zopfli_uncompress() : error conditions ***\n";

// Zero arguments
echo "\n-- Testing zopfli_uncompress() function with Zero arguments --\n";
var_dump( zopfli_uncompress() );

//Test zopfli_uncompress with one more than the expected number of arguments
echo "\n-- Testing zopfli_uncompress() function with more than expected no. of arguments --\n";
$data = 'string_val';
$length = 10;
$extra_arg = 10;
var_dump( zopfli_uncompress($data, $length, $extra_arg) );

echo "\n-- Testing with a buffer that is too small --\n";
$short_len = strlen($data) - 1;
$compressed = zopfli_compress($data);

var_dump(zopfli_uncompress($compressed, $short_len));

echo "\n-- Testing with incorrect arguments --\n";
var_dump(zopfli_uncompress(123));

class Tester {
    function Hello() {
        echo "Hello\n";
    }
}

$testclass = new Tester();
var_dump(zopfli_uncompress($testclass));

var_dump(zopfli_uncompress($compressed, "this is not a number\n"));

?>
===DONE===
--EXPECTF--
*** Testing zopfli_uncompress() : error conditions ***

-- Testing zopfli_uncompress() function with Zero arguments --

Warning: %suncompress() expects at least 1 parameter, 0 given in %s on line %d
NULL

-- Testing zopfli_uncompress() function with more than expected no. of arguments --

Warning: %suncompress() expects at most 2 parameters, 3 given in %s on line %d
NULL

-- Testing with a buffer that is too small --

Warning: %suncompress(): insufficient memory in %s on line %d
bool(false)

-- Testing with incorrect arguments --

Warning: %suncompress(): data error in %s on line %d
bool(false)

Warning: %suncompress() expects parameter 1 to be string, object given in %s on line %d
NULL

Warning: %suncompress() expects parameter 2 to be long, string given in %s on line %d
NULL
===DONE===
