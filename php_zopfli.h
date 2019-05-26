
#ifndef PHP_ZOPFLI_H
#define PHP_ZOPFLI_H

#define ZOPFLI_EXT_VERSION "0.3.0"

#define ZOPFLI_TYPE_GZIP    0x1e
#define ZOPFLI_TYPE_ZLIB    0x2e
#define ZOPFLI_TYPE_DEFLATE 0x0e

extern zend_module_entry zopfli_module_entry;
#define phpext_zopfli_ptr &zopfli_module_entry

#endif  /* PHP_ZOPFLI_H */
