--TEST--
Test phpinfo() displays zopfli info
--SKIPIF--
--FILE--
<?php
if (!extension_loaded('zopfli')) {
    dl('zopfli.' . PHP_SHLIB_SUFFIX);
}

phpinfo();
--EXPECTF--
%a
zopfli

Zopfli support => enabled
Extension Version => %d.%d.%d
%a
