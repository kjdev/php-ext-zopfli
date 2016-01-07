--TEST--
Test zofpli_deflate() function : error conditions
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

/*
 * add a comment here to say what the test is supposed to do
 */

echo "*** Testing zopfli_deflate() : error conditions ***\n";

// Zero arguments
echo "\n-- Testing zopfli_deflate() function with Zero arguments --\n";
var_dump( zopfli_deflate() );

//Test zopfli_deflate with one more than the expected number of arguments
echo "\n-- Testing zopfli_deflate() function with more than expected no. of arguments --\n";
$data = 'string_val';
$iteration = 2;
$encoding = ZOPFLI_GZIP;
$extra_arg = 10;
var_dump( zopfli_deflate($data, $iteration, $encoding, $extra_arg) );

echo "\n-- Testing with incorrect iteration --\n";
$bad_iteration = -1;
var_dump(zopfli_deflate($data, $bad_iteration));

echo "\n-- Testing with incorrect encoding --\n";
$bad_encoding = 99;
var_dump(zopfli_deflate($data, $iteration, $bad_encoding));

class Tester {
    function Hello() {
        echo "Hello\n";
    }
}

echo "\n-- Testing with incorrect parameters --\n";
$testclass = new Tester();
var_dump(zopfli_deflate($testclass));
var_dump(zopfli_deflate($data, $testclass));

?>
===Done===
--EXPECTF--
*** Testing zopfli_deflate() : error conditions ***

-- Testing zopfli_deflate() function with Zero arguments --

Warning: zopfli_deflate() expects at least 1 parameter, 0 given in %s on line %d
NULL

-- Testing zopfli_deflate() function with more than expected no. of arguments --

Warning: zopfli_deflate() expects at most 3 parameters, 4 given in %s on line %d
NULL

-- Testing with incorrect iteration --

Warning: zopfli_deflate(): compression iterations (-1) must be greater than 0 in %s on line %d
bool(false)

-- Testing with incorrect encoding --

Warning: zopfli_deflate(): type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE in %s on line %d
bool(false)

-- Testing with incorrect parameters --

Warning: zopfli_deflate() expects parameter 1 to be string, object given in %s on line %d
NULL

Warning: zopfli_deflate() expects parameter 2 to be %s, object given in %s on line %d
NULL
===Done===
