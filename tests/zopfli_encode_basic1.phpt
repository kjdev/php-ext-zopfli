--TEST--
Test zofpli_encode() function : basic functionality
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

/*
 * Test basic function of zopfli_encode
 */

include(dirname(__FILE__) . '/data.inc');

echo "*** Testing zopfli_encode() : basic functionality ***\n";

// Initialise all required variables

$smallstring = "A small string to compress\n";


// Calling zopfli_encode() with various iterations

// Compressing a big string
for($i = 1; $i < 10; $i++) {
    echo "-- Iteration $i --\n";
    $output = zopfli_encode($data, $i);

    // Clear OS byte before encode
    $output[9] = "\x00";

    var_dump(md5($output));
}

// Compressing a smaller string
for($i = 1; $i < 10; $i++) {
    echo "-- Iteration $i --\n";
    $output = zopfli_encode($smallstring, $i);

    // Clear OS byte before encode
    $output[9] = "\x00";

    var_dump(md5($output));
}

// Calling zopfli_encode() with mandatory arguments
echo "\n-- Testing with no specified iteration --\n";
var_dump(bin2hex(zopfli_encode($smallstring)));

echo "\n-- Testing zopfli_encode with mode specified --\n";
var_dump(bin2hex(zopfli_encode($smallstring, 1, ZOPFLI_GZIP)));

?>
===Done===
--EXPECTF--
*** Testing zopfli_encode() : basic functionality ***
-- Iteration 1 --
string(32) "c47f8d51e9a3a3015b3a4a728c90b47d"
-- Iteration 2 --
string(32) "4d2038499581ecfda70dfe41607b8364"
-- Iteration 3 --
string(32) "a5287642c204dbe69fa83d2de9c97723"
-- Iteration 4 --
string(32) "a5287642c204dbe69fa83d2de9c97723"
-- Iteration 5 --
string(32) "a5287642c204dbe69fa83d2de9c97723"
-- Iteration 6 --
string(32) "a5287642c204dbe69fa83d2de9c97723"
-- Iteration 7 --
string(32) "a5287642c204dbe69fa83d2de9c97723"
-- Iteration 8 --
string(32) "a5287642c204dbe69fa83d2de9c97723"
-- Iteration 9 --
string(32) "a5287642c204dbe69fa83d2de9c97723"
-- Iteration 1 --
string(32) "8849e9a1543c04b3f882b5ce20839ed2"
-- Iteration 2 --
string(32) "8849e9a1543c04b3f882b5ce20839ed2"
-- Iteration 3 --
string(32) "8849e9a1543c04b3f882b5ce20839ed2"
-- Iteration 4 --
string(32) "8849e9a1543c04b3f882b5ce20839ed2"
-- Iteration 5 --
string(32) "8849e9a1543c04b3f882b5ce20839ed2"
-- Iteration 6 --
string(32) "8849e9a1543c04b3f882b5ce20839ed2"
-- Iteration 7 --
string(32) "8849e9a1543c04b3f882b5ce20839ed2"
-- Iteration 8 --
string(32) "8849e9a1543c04b3f882b5ce20839ed2"
-- Iteration 9 --
string(32) "8849e9a1543c04b3f882b5ce20839ed2"

-- Testing with no specified iteration --
string(94) "1f8b0800000000000203735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200edc4e40b1b000000"

-- Testing zopfli_encode with mode specified --
string(94) "1f8b0800000000000203735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200edc4e40b1b000000"
===Done===
