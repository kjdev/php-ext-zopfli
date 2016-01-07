--TEST--
Test zofpli_inflate() function : error conditions
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

include(dirname(__FILE__) . '/data.inc');

echo "*** Testing zopfli_inflate() : error conditions ***\n";

echo "\n-- Testing zopfli_inflate() function with Zero arguments --\n";
var_dump( zopfli_inflate() );

echo "\n-- Testing zopfli_inflate() function with more than expected no. of arguments --\n";
$data = 'string_val';
$length = 10;
$extra_arg = 10;
var_dump( zopfli_inflate($data, $length, $extra_arg) );

echo "\n-- Testing with a buffer that is too small --\n";
$short_len = strlen($data) - 1;
$compressed = zopfli_compress($data);

var_dump(zopfli_inflate($compressed, $short_len));

echo "\n-- Testing with incorrect parameters --\n";

class Tester {
    function Hello() {
        echo "Hello\n";
    }
}

$testclass = new Tester();
var_dump(zopfli_inflate($testclass));
var_dump(zopfli_inflate($data, $testclass));

?>
===DONE===
--EXPECTF--
*** Testing zopfli_inflate() : error conditions ***

-- Testing zopfli_inflate() function with Zero arguments --

Warning: %sinflate() expects at least 1 parameter, 0 given in %s on line %d
NULL

-- Testing zopfli_inflate() function with more than expected no. of arguments --

Warning: %sinflate() expects at most 2 parameters, 3 given in %s on line %d
NULL

-- Testing with a buffer that is too small --

Warning: %sinflate(): data error in %s on line %d
bool(false)

-- Testing with incorrect parameters --

Warning: %sinflate() expects parameter 1 to be string, object given in %s on line %d
NULL

Warning: %sinflate() expects parameter 2 to be %s, object given in %s on line %d
NULL
===DONE===
