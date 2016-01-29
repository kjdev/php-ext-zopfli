--TEST--
Test zofpli_compress() function : basic functionality
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

/*
 * add a comment here to say what the test is supposed to do
 */

include(dirname(__FILE__) . '/data.inc');

echo "*** Testing zopfli_compress() : basic functionality ***\n";

// Initialise all required variables

$smallstring = "A small string to compress\n";


// Calling zopfli_compress() with all possible arguments

// Compressing a big string
for($i = 1; $i < 10; $i++) {
    echo "-- Iterations $i --\n";
    $output = zopfli_compress($data, $i);
    var_dump(md5($output));
    var_dump(strcmp(zopfli_uncompress($output), $data));
}

// Compressing a smaller string
for($i = 1; $i < 10; $i++) {
    echo "-- Iterations $i --\n";
    $output = zopfli_compress($smallstring, $i);
    var_dump(bin2hex($output));
    var_dump(strcmp(zopfli_uncompress($output), $smallstring));
}

// Calling zopfli_compress() with mandatory arguments
echo "\n-- Testing with no specified iterations --\n";
var_dump( bin2hex(zopfli_compress($smallstring) ));

?>
===Done===
--EXPECT--
*** Testing zopfli_compress() : basic functionality ***
-- Iterations 1 --
string(32) "aa9ec697ac05881a56befa5d1c2176fe"
int(0)
-- Iterations 2 --
string(32) "b3d945d2cfdbc9aaf2e5e8d283b235dc"
int(0)
-- Iterations 3 --
string(32) "b2000ecc797222480a58ec6c2d6ae605"
int(0)
-- Iterations 4 --
string(32) "b2000ecc797222480a58ec6c2d6ae605"
int(0)
-- Iterations 5 --
string(32) "b2000ecc797222480a58ec6c2d6ae605"
int(0)
-- Iterations 6 --
string(32) "b2000ecc797222480a58ec6c2d6ae605"
int(0)
-- Iterations 7 --
string(32) "b2000ecc797222480a58ec6c2d6ae605"
int(0)
-- Iterations 8 --
string(32) "b2000ecc797222480a58ec6c2d6ae605"
int(0)
-- Iterations 9 --
string(32) "b2000ecc797222480a58ec6c2d6ae605"
int(0)
-- Iterations 1 --
string(70) "7801735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee6020087a509cb"
int(0)
-- Iterations 2 --
string(70) "7801735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee6020087a509cb"
int(0)
-- Iterations 3 --
string(70) "7801735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee6020087a509cb"
int(0)
-- Iterations 4 --
string(70) "7801735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee6020087a509cb"
int(0)
-- Iterations 5 --
string(70) "7801735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee6020087a509cb"
int(0)
-- Iterations 6 --
string(70) "7801735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee6020087a509cb"
int(0)
-- Iterations 7 --
string(70) "7801735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee6020087a509cb"
int(0)
-- Iterations 8 --
string(70) "7801735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee6020087a509cb"
int(0)
-- Iterations 9 --
string(70) "7801735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee6020087a509cb"
int(0)

-- Testing with no specified iterations --
string(70) "7801735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee6020087a509cb"
===Done===
