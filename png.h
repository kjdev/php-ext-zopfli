
#ifndef PHP_ZOPFLI_PNG_H
#define PHP_ZOPFLI_PNG_H

#include <zlib.h>

#define ZOPFLI_PNG_SIGNATURE_SIZE  8
#define ZOPFLI_PNG_IHDR_SIZE      25
#define ZOPFLI_PNG_IEND_SIZE      12

#if defined(HAVE_INTTYPES_H)
#include <inttypes.h>
#elif defined(HAVE_STDINT_H)
#include <stdint.h>
#endif

int php_zopfli_is_invalid_signature(unsigned char *in);
uint32_t php_zopfli_read_uint32(unsigned char *in, uint32_t *ipos);
void php_zopfli_write_uint32(unsigned char *out, uint32_t *opos, uint32_t data);
uLongf php_zopfli_calc_inflate_buf_size(unsigned char *in, uint32_t *ipos);

#endif
