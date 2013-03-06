--TEST--
Test zofpli_deflate() function : basic functionality
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

echo "*** Testing zopfli_deflate() : basic functionality ***\n";

// Initialise all required variables

$smallstring = "A small string to compress\n";


// Calling zopfli_deflate() with all possible arguments

// Compressing a big string
for($i = 1; $i < 10; $i++) {
    echo "-- Iterations $i --\n";
    $output = zopfli_deflate($data, $i);
    var_dump(md5($output));
    var_dump(strcmp(zopfli_inflate($output), $data));
}

// Compressing a smaller string
for($i = 1; $i < 10; $i++) {
    echo "-- Iterations $i --\n";
    $output = zopfli_deflate($smallstring, $i);
    var_dump(bin2hex($output));
    var_dump(strcmp(zopfli_inflate($output), $smallstring));
}

// Calling zopfli_deflate() with just mandatory arguments
echo "\n-- Testing with no specified iterations --\n";
var_dump( bin2hex(zopfli_deflate($smallstring) ));

?>
===Done===
--EXPECT--
*** Testing zopfli_deflate() : basic functionality ***
-- Iterations 1 --
string(32) "c51e476d8ade3ff8736a94d126b93489"
int(0)
-- Iterations 2 --
string(32) "a86e09633b5fafa6ff62dc11aa653041"
int(0)
-- Iterations 3 --
string(32) "cb531d26850282df19b49ccc1767ae0d"
int(0)
-- Iterations 4 --
string(32) "cb531d26850282df19b49ccc1767ae0d"
int(0)
-- Iterations 5 --
string(32) "cb531d26850282df19b49ccc1767ae0d"
int(0)
-- Iterations 6 --
string(32) "cb531d26850282df19b49ccc1767ae0d"
int(0)
-- Iterations 7 --
string(32) "cb531d26850282df19b49ccc1767ae0d"
int(0)
-- Iterations 8 --
string(32) "cb531d26850282df19b49ccc1767ae0d"
int(0)
-- Iterations 9 --
string(32) "cb531d26850282df19b49ccc1767ae0d"
int(0)
-- Iterations 1 --
string(58) "735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200"
int(0)
-- Iterations 2 --
string(58) "735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200"
int(0)
-- Iterations 3 --
string(58) "735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200"
int(0)
-- Iterations 4 --
string(58) "735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200"
int(0)
-- Iterations 5 --
string(58) "735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200"
int(0)
-- Iterations 6 --
string(58) "735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200"
int(0)
-- Iterations 7 --
string(58) "735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200"
int(0)
-- Iterations 8 --
string(58) "735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200"
int(0)
-- Iterations 9 --
string(58) "735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200"
int(0)

-- Testing with no specified iterations --
string(58) "735428ce4dccc951282e29cacc4b5728c95748cecf2d284a2d2ee60200"
===Done===
