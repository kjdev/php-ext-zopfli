# Zopfli Extension for PHP #

[![Build Status](https://travis-ci.org/kjdev/php-ext-zopfli.png?branch=master)](http://travis-ci.org/kjdev/php-ext-zopfli)

This extension allows Zopfli compression.

Documentation for Zopfli can be found at [» https://github.com/google/zopfli](https://github.com/google/zopfli).

## Build ##

    % phpize
    % ./configure
    % make
    $ make install

## Configration ##

zopfli.ini:

    extension=zopfli.so

## Function ##

* zopfli\_encode — Create a gzip compressed string
* zopfli\_compress — Compress a string
* zopfli\_deflate — Deflate a string
* zopfli\_decode — Decodes a gzip compressed string
* zopfli\_uncompress — Uncompress a compressed string
* zopfli\_inflate — Inflate a deflated string
* zopfli\_png_recompress — Recompress IDAT chunks in a PNG Image

## zopfli\_encode — Create a gzip compressed string ##

### Description ###

string **zopfli\_encode** ( string _$data_ [, int _$iteration_ = 15 [, int _$encoding_ = ZOPFLI\_GZIP ]] )

This function returns a compressed version of the input data compatible with the
output of the gzip program.

### Parameters ###

* _data_

  The data to encode.

* _iteration_

  The iteration of compression. Specify a value greater than 0.

* _encoding_

  The encoding mode. Can be ZOPFLI\_GZIP (the default) or ZOPFLI\_DEFLATE,
  ZOPFLI\_ZLIB.

### Return Values ###

The encoded string, or FALSE if an error occurred.


## zopfli\_compress — Compress a string ##

### Description ###

string **zopfli\_compress** ( string _$data_ [, int _$iteration_ = 15 ] )

This function compress the given string using the ZLIB data format.

### Parameters ###

* _data_

  The data to compress.

* _iteration_

  The iteration of compression. Specify a value greater than 0.


### Return Values ###

The compressed string or FALSE if an error occurred.


## zopfli\_deflate — Deflate a string ##

### Description ###

string **zopfli\_deflate** ( string _$data_ [, int _$iteration_ = 15 ] )

This function compress the given string using the DEFLATE data format.

### Parameters ###

* _data_

  The data to deflate.

* _iteration_

  The iteration of compression. Specify a value greater than 0.

### Return Values ###

The deflated string or FALSE if an error occurred.


## zopfli\_decode — Decodes a gzip compressed string ##

### Description ###

string **zopfli\_decode** ( string _$data_ [, int _$length_ = 0 ] )

This function returns a decoded version of the input data.

same as gzdecode().

### Pameters ###

* _data_

  The data to decode, encoded by zopfli\_encode(), gzencode().

* _length_

  The maximum length of data to decode.

### Return Values ###

The decoded string, or FALSE if an error occurred.


### zopfli\_uncompress — Uncompress a compressed string ###

### Description ###

string **zopfli\_uncompress** ( string _$data_ [, int _$length_ = 0 ] )

This function uncompress a compressed string.

same as gzuncompress().

### Parameters ###

* _data_

  The data compressed by zopfli\_uncompress(), gzcompress().

* _length_

  The maximum length of data to decode.

### Return Values ###

The original uncompressed data or FALSE on error.


## zopfli\_inflate — Inflate a deflated string ##

### Description ###

string **zopfli\_inflate** ( string _$data_ [, int _$length_ = 0 ] )

This function inflate a deflated string.

same as gzinfrate().

### Parameters ###

* _data_

  The data compressed by zopfli\_deflate(), gzdeflate().

* _length_

  The maximum length of data to decode.

### Return Values ###

The original uncompressed data or FALSE on error.


## zopfli\_png\_recompress — Recommress IDAT chunks in PNG Image ##

### Description ###

string **zopfli\_png\_recompress** ( string _$data_ [, int _$iteration_ = 15 ] )

This function recompresses IDAT chunks in a PNG Image.

### Parameters ###

* _data_

  The PNG Image

* _iteration_

  The iteration of compression. Specify a value greater than 0.

### Return Values ###

The recompressed PNG Image or FALSE on error.


## Examples ##

    $data = zopfli_encode('test');

    zopfli_decode($data);
    //gzdecode($data);

    $data = zopfli_compress('test');

    zopfli_uncompress($data);
    //gzuncompress($data);

    $data = zopfli_deflate('test');

    zopfli_inflate($data);
    //gzinflate($data);

    $data = file_get_contents('original.png');
    $recompress = zopfli_png_recompress($data);
    file_put_contents('recompress.png', $recompress);

## Related ##

* [code coverage report](http://gcov.at-ninja.jp.2-t.jp/6X)
* [api document](http://api.at-ninja.jp.2-t.jp/6Y)
