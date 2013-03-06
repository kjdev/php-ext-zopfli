--TEST--
Test zofpli_compress() function : error conditions
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

/*
 * add a comment here to say what the test is supposed to do
 */

echo "*** Testing zopfli_compress() : error conditions ***\n";

// Zero arguments
echo "\n-- Testing zopfli_compress() function with Zero arguments --\n";
var_dump( zopfli_compress() );

//Test zopfli_compress with one more than the expected number of arguments
echo "\n-- Testing zopfli_compress() function with more than expected no. of arguments --\n";
$data = 'string_val';
$iteration = 2;
$encoding = ZOPFLI_GZIP;
$extra_arg = 10;
var_dump( zopfli_compress($data, $iteration, $encoding, $extra_arg) );

echo "\n-- Testing with incorrect compression iteration --\n";
$bad_iteration = -1;
var_dump(zopfli_compress($data, $bad_iteration));

echo "\n-- Testing with invalid encoding --\n";
$data = 'string_val';
$encoding = 99;
var_dump(zopfli_compress($data, $iteration, $encoding));

echo "\n-- Testing with incorrect parameters --\n";

class Tester {
    function Hello() {
        echo "Hello\n";
    }
}

$testclass = new Tester();
var_dump(zopfli_compress($testclass));

?>
===Done===
--EXPECTF--
*** Testing zopfli_compress() : error conditions ***

-- Testing zopfli_compress() function with Zero arguments --

Warning: zopfli_compress() expects at least 1 parameter, 0 given in %s on line %d
NULL

-- Testing zopfli_compress() function with more than expected no. of arguments --

Warning: zopfli_compress() expects at most 3 parameters, 4 given in %s on line %d
NULL

-- Testing with incorrect compression iteration --

Warning: zopfli_compress(): compression iterations (-1) must be greater than 0 in %s on line %d
bool(false)

-- Testing with invalid encoding --

Warning: zopfli_compress(): type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE in %s on line %d
bool(false)

-- Testing with incorrect parameters --

Warning: zopfli_compress() expects parameter 1 to be string, object given in %s on line %d
NULL
===Done===
