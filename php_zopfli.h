
#ifndef PHP_ZOPFLI_H
#define PHP_ZOPFLI_H

#define ZOPFLI_EXT_VERSION "0.2.1"

#define ZOPFLI_TYPE_GZIP    0x1e
#define ZOPFLI_TYPE_ZLIB    0x2e
#define ZOPFLI_TYPE_DEFLATE 0x0e

extern zend_module_entry zopfli_module_entry;
#define phpext_zopfli_ptr &zopfli_module_entry

#ifdef PHP_WIN32
#    define PHP_ZOPFLI_API __declspec(dllexport)
#elif defined(__GNUC__) && __GNUC__ >= 4
#    define PHP_ZOPFLI_API __attribute__ ((visibility("default")))
#else
#    define PHP_ZOPFLI_API
#endif

#ifdef ZTS
#    include "TSRM.h"
#endif

#ifdef ZTS
#    define ZOPFLI_G(v) TSRMG(zopfli_globals_id, zend_zopfli_globals *, v)
#else
#    define ZOPFLI_G(v) (zopfli_globals.v)
#endif

#endif  /* PHP_ZOPFLI_H */
