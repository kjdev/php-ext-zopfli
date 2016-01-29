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
string(32) "b964e579756db2dd3def27798accbe93"
int(0)
-- Iterations 2 --
string(32) "a9d3144eea31e0a9859184da990a02fc"
int(0)
-- Iterations 3 --
string(32) "44a09b1d30aaa109b3acb3cd24b93421"
int(0)
-- Iterations 4 --
string(32) "44a09b1d30aaa109b3acb3cd24b93421"
int(0)
-- Iterations 5 --
string(32) "44a09b1d30aaa109b3acb3cd24b93421"
int(0)
-- Iterations 6 --
string(32) "44a09b1d30aaa109b3acb3cd24b93421"
int(0)
-- Iterations 7 --
string(32) "44a09b1d30aaa109b3acb3cd24b93421"
int(0)
-- Iterations 8 --
string(32) "44a09b1d30aaa109b3acb3cd24b93421"
int(0)
-- Iterations 9 --
string(32) "44a09b1d30aaa109b3acb3cd24b93421"
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
