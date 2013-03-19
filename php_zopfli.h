
#ifndef PHP_ZOPFLI_H
#define PHP_ZOPFLI_H

#include <zlib.h>

#define ZOPFLI_EXT_VERSION "0.1.0"

#define ZOPFLI_TYPE_GZIP    0x1e
#define ZOPFLI_TYPE_ZLIB    0x2e
#define ZOPFLI_TYPE_DEFLATE 0x0e

#define ZOPFLI_PNG_SIGNATURE_SIZE  8
#define ZOPFLI_PNG_IHDR_SIZE      25
#define ZOPFLI_PNG_IEND_SIZE      12

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

#if defined(HAVE_INTTYPES_H)
#include <inttypes.h>
#elif defined(HAVE_STDINT_H)
#include <stdint.h>
#endif

int php_zopfli_is_invalid_signature(unsigned char *in);
uint32_t php_zopfli_read_uint32(unsigned char *in, uint32_t *ipos);
void php_zopfli_write_uint32(unsigned char *out, uint32_t *opos, uint32_t data);
uLongf php_zopfli_calc_inflate_buf_size(unsigned char *in, uint32_t *ipos);

#endif  /* PHP_ZOPFLI_H */
