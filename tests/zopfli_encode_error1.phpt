--TEST--
Test zofpli_encode() function : error conditions
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

/*
 * Test error cases for zopfli_encode
 */

echo "*** Testing zopfli_encode() : error conditions ***\n";

// Zero arguments
echo "\n-- Testing zopfli_encode() function with Zero arguments --\n";
var_dump( zopfli_encode() );

//Test zopfli_encode with one more than the expected number of arguments
echo "\n-- Testing zopfli_encode() function with more than expected no. of arguments --\n";
$data = 'string_val';
$iteration = 2;
$encoding_mode = ZOPFLI_GZIP;
$extra_arg = 10;
var_dump( zopfli_encode($data, $iteration, $encoding_mode, $extra_arg) );

echo "\n-- Testing with incorrect compression iteration --\n";
$bad_iteration = -1;
var_dump(zopfli_encode($data, $bad_iteration));

echo "\n-- Testing with incorrect encoding_mode --\n";
$bad_mode = 99;
var_dump(zopfli_encode($data, $iteration, $bad_mode));

class Tester {
    function Hello() {
        echo "Hello\n";
    }
}

echo "\n-- Testing with incorrect parameters --\n";
$testclass = new Tester();
var_dump(zopfli_encode($testclass));
var_dump(zopfli_encode($data, $testclass));
var_dump(zopfli_encode($data, 1, 99.99));
var_dump(zopfli_encode($data, 1, $testclass));
var_dump(zopfli_encode($data, "a very none numeric string\n"));

?>
===Done===
--EXPECTF--
*** Testing zopfli_encode() : error conditions ***

-- Testing zopfli_encode() function with Zero arguments --

Warning: zopfli_encode() expects at least 1 parameter, 0 given in %s on line %d
NULL

-- Testing zopfli_encode() function with more than expected no. of arguments --

Warning: zopfli_encode() expects at most 3 parameters, 4 given in %s on line %d
NULL

-- Testing with incorrect compression iteration --

Warning: zopfli_encode(): compression iterations (-1) must be greater than 0 in %s on line %d
bool(false)

-- Testing with incorrect encoding_mode --

Warning: zopfli_encode(): type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE in %s on line %d
bool(false)

-- Testing with incorrect parameters --

Warning: zopfli_encode() expects parameter 1 to be string, object given in %s on line %d
NULL

Warning: zopfli_encode() expects parameter 2 to be %s, object given in %s on line %d
NULL

Warning: zopfli_encode(): type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE in %s on line %d
bool(false)

Warning: zopfli_encode() expects parameter 3 to be %s, object given in %s on line %d
NULL

Warning: zopfli_encode() expects parameter 2 to be %s, string given in %s on line %d
NULL
===Done===
