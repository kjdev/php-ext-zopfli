<?php
/**
 * Generated stub file for code completion purposes
 */

const ZOPFLI_GZIP = 30;

const ZOPFLI_ZLIB = 46;

const ZOPFLI_DEFLATE = 14;

/**
 * This function returns a compressed version of the input data compatible with the output of the gzip program.
 *
 * @param string $data The data to encode.
 * @param int    $iteration The iteration of compression. Specify a value greater than 0.
 * @param int    $output_type The encoding mode. Can be ZOPFLI_GZIP (the default) or ZOPFLI_DEFLATE, ZOPFLI_ZLIB.
 *
 * @return string|bool The encoded string, or FALSE if an error occurred.
 */
function zopfli_encode($data, $iteration = 15, $output_type = ZOPFLI_GZIP){}

/**
 * This function compress the given string using the ZLIB data format.
 *
 * @param string $data The data to compress.
 * @param int    $iteration The iteration of compression. Specify a value greater than 0.
 *
 * @return string|bool The encoded string, or FALSE if an error occurred.
 */
function zopfli_compress($data, $iteration = 15){}

/**
 * This function compress the given string using the DEFLATE data format.
 *
 * @param string $data The data to compress.
 * @param int    $iteration The iteration of compression. Specify a value greater than 0.
 *
 * @return string|bool The encoded string, or FALSE if an error occurred.
 */
function zopfli_deflate($data, $iteration = 15){}

/**
 * This function returns a decoded version of the input data.
 * same as gzdecode().
 *
 * @param string $data The data to decode, encoded by zopfli_encode(), gzencode().
 * @param int    $max The maximum length of data to decode.
 * @param int    $input_type
 *
 * @return string|bool The decoded string, or FALSE if an error occurred.
 */
function zopfli_decode($data, $max = 0, $input_type = ZOPFLI_GZIP){}

/**
 * This function uncompress a compressed string.
 * same as gzuncompress().
 *
 * @param string $data The data compressed by zopfli_compress(), gzcompress().
 * @param int    $max The maximum length of data to decode.
 *
 * @return string|bool The original uncompressed data or FALSE on error.
 */
function zopfli_uncompress($data, $max = 0){}

/**
 * This function inflate a deflated string.
 * same as gzinflate().
 *
 * @param string $data The data compressed by zopfli_deflate(), gzdeflate().
 * @param int    $max The maximum length of data to decode.
 *
 * @return string|bool The original uncompressed data or FALSE on error.
 */
function zopfli_inflate($data, $max = 0){}

/**
 * This function recompresses IDAT chunks in a PNG Image.
 *
 * @param string $data The PNG Image
 * @param int    $iteration The iteration of compression. Specify a value greater than 0.
 *
 * @return string|bool The recompressed PNG Image or FALSE on error.
 */
function zopfli_png_recompress($data, $iteration = 15){}
