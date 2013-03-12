
#ifdef HAVE_CONFIG_H
#    include "config.h"
#endif

#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "php_verdep.h"
#include "php_zopfli.h"

/* zopfli */
#include "zopfli/deflate.h"
#include "zopfli/gzip_container.h"
#include "zopfli/zlib_container.h"

static ZEND_FUNCTION(zopfli_encode);
static ZEND_FUNCTION(zopfli_compress);
static ZEND_FUNCTION(zopfli_deflate);
static ZEND_FUNCTION(zopfli_decode);
static ZEND_FUNCTION(zopfli_uncompress);
static ZEND_FUNCTION(zopfli_inflate);

ZEND_BEGIN_ARG_INFO_EX(arginfo_zopfli_encode, 0, 0, 1)
    ZEND_ARG_INFO(0, data)
    ZEND_ARG_INFO(0, iteration)
    ZEND_ARG_INFO(0, output_type)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zopfli_compress, 0, 0, 1)
    ZEND_ARG_INFO(0, data)
    ZEND_ARG_INFO(0, iteration)
    ZEND_ARG_INFO(0, output_type)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zopfli_deflate, 0, 0, 1)
    ZEND_ARG_INFO(0, data)
    ZEND_ARG_INFO(0, iteration)
    ZEND_ARG_INFO(0, output_type)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zopfli_decode, 0, 0, 1)
    ZEND_ARG_INFO(0, data)
    ZEND_ARG_INFO(0, max)
    ZEND_ARG_INFO(0, input_type)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zopfli_uncompress, 0, 0, 1)
    ZEND_ARG_INFO(0, data)
    ZEND_ARG_INFO(0, max)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_zopfli_inflate, 0, 0, 1)
    ZEND_ARG_INFO(0, data)
    ZEND_ARG_INFO(0, max)
ZEND_END_ARG_INFO()

static zend_function_entry zopfli_functions[] = {
    ZEND_FE(zopfli_encode, arginfo_zopfli_encode)
    ZEND_FE(zopfli_compress, arginfo_zopfli_compress)
    ZEND_FE(zopfli_deflate, arginfo_zopfli_deflate)
    ZEND_FE(zopfli_decode, arginfo_zopfli_decode)
    ZEND_FE(zopfli_uncompress, arginfo_zopfli_uncompress)
    ZEND_FE(zopfli_inflate, arginfo_zopfli_inflate)
    ZEND_FE_END
};

ZEND_MINIT_FUNCTION(zopfli)
{
    REGISTER_LONG_CONSTANT("ZOPFLI_GZIP", ZOPFLI_TYPE_GZIP,
                           CONST_CS | CONST_PERSISTENT);
    REGISTER_LONG_CONSTANT("ZOPFLI_ZLIB", ZOPFLI_TYPE_ZLIB,
                           CONST_CS | CONST_PERSISTENT);
    REGISTER_LONG_CONSTANT("ZOPFLI_DEFLATE", ZOPFLI_TYPE_DEFLATE,
                           CONST_CS | CONST_PERSISTENT);
    return SUCCESS;
}

ZEND_MSHUTDOWN_FUNCTION(zopfli)
{
    return SUCCESS;
}

ZEND_MINFO_FUNCTION(zopfli)
{
    php_info_print_table_start();
    php_info_print_table_row(2, "Zopfli support", "enabled");
    php_info_print_table_row(2, "Extension Version", ZOPFLI_EXT_VERSION);
    php_info_print_table_end();
}

zend_module_entry zopfli_module_entry = {
#if ZEND_MODULE_API_NO >= 20010901
    STANDARD_MODULE_HEADER,
#endif
    "zopfli",
    zopfli_functions,
    ZEND_MINIT(zopfli),
    ZEND_MSHUTDOWN(zopfli),
    NULL,
    NULL,
    ZEND_MINFO(zopfli),
#if ZEND_MODULE_API_NO >= 20010901
    ZOPFLI_EXT_VERSION,
#endif
    STANDARD_MODULE_PROPERTIES
};

#ifdef COMPILE_DL_ZOPFLI
ZEND_GET_MODULE(zopfli)
#endif

static inline int
php_zopfli_encode(unsigned char *in, size_t in_size, int iteration,
                  unsigned int out_type, unsigned char **out, size_t *out_size
                  TSRMLS_DC)
{
    ZopfliOptions options;

    ZopfliInitOptions(&options);
    options.numiterations = iteration;

    *out = NULL;
    *out_size = 0;

    if (out_type == ZOPFLI_TYPE_GZIP) {
        ZopfliGzipCompress(&options, in, in_size, out, out_size);
    } else if (out_type == ZOPFLI_TYPE_ZLIB) {
        ZopfliZlibCompress(&options, in, in_size, out, out_size);
    } else if (out_type == ZOPFLI_TYPE_DEFLATE) {
        unsigned char bp = 0;
        ZopfliDeflate(&options, 2, 1, in, in_size, &bp, out, out_size);
    } else {
        return FAILURE;
    }

    return SUCCESS;
}

#define PHP_ZOPFLI_ENCODE_FUNC(_name, _type) \
static ZEND_FUNCTION(_name) \
{ \
    long iteration = 15; \
    char *in, *out = NULL; \
    int in_size; \
    size_t out_size = 0; \
    long out_type = _type; \
    if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "s|ll", &in, &in_size, &iteration, &out_type) == FAILURE) { \
        return; \
    } \
    if (iteration <= 0) { \
        php_error_docref(NULL TSRMLS_CC, E_WARNING, "compression iterations (%ld) must be greater than 0", iteration); \
        RETURN_FALSE; \
    } \
    switch (out_type) { \
        case ZOPFLI_TYPE_GZIP: \
        case ZOPFLI_TYPE_ZLIB: \
        case ZOPFLI_TYPE_DEFLATE: \
            break; \
        default: \
            php_error_docref(NULL TSRMLS_CC, E_WARNING, "type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE"); \
            RETURN_FALSE; \
    } \
    if (php_zopfli_encode((unsigned char *)in, in_size, iteration, out_type, (unsigned char **)&out, &out_size TSRMLS_CC) != SUCCESS) { \
        RETURN_FALSE; \
    } \
    RETVAL_STRINGL(out, out_size, 1); \
    free(out); \
}

PHP_ZOPFLI_ENCODE_FUNC(zopfli_encode, ZOPFLI_TYPE_GZIP);

PHP_ZOPFLI_ENCODE_FUNC(zopfli_compress, ZOPFLI_TYPE_ZLIB);

PHP_ZOPFLI_ENCODE_FUNC(zopfli_deflate, ZOPFLI_TYPE_DEFLATE);


#ifdef HAVE_ZLIB_H

#include <zlib.h>
#define PHP_ZLIB_ENCODING_RAW     -0xf
#define PHP_ZLIB_ENCODING_GZIP    0x1f
#define PHP_ZLIB_ENCODING_DEFLATE 0x0f
#define PHP_ZLIB_ENCODING_ANY     0x2f

typedef struct _php_zlib_buffer {
    char *data;
    char *aptr;
    size_t used;
    size_t free;
    size_t size;
} php_zlib_buffer;

static voidpf
php_zlib_alloc(voidpf opaque, uInt items, uInt size)
{
    return (voidpf)safe_emalloc(items, size, 0);
}

static void
php_zlib_free(voidpf opaque, voidpf address)
{
    efree((void*)address);
}

static inline int
php_zopfli_inflate_rounds(z_stream *Z, size_t max, char **buf, size_t *len)
{
    int status, round = 0;
    php_zlib_buffer buffer = {NULL, NULL, 0, 0, 0};

    *buf = NULL;
    *len = 0;

    buffer.size = (max && (max < Z->avail_in)) ? max : Z->avail_in;

    do {
        if ((max && (max <= buffer.used)) ||
            !(buffer.aptr = erealloc_recoverable(buffer.data, buffer.size))) {
            status = Z_MEM_ERROR;
        } else {
            buffer.data = buffer.aptr;
            Z->avail_out = buffer.free = buffer.size - buffer.used;
            Z->next_out = (Bytef *)buffer.data + buffer.used;
            status = inflate(Z, Z_NO_FLUSH);
            buffer.used += buffer.free - Z->avail_out;
            buffer.free = Z->avail_out;
            buffer.size += (buffer.size >> 3) + 1;
        }
    } while ((Z_BUF_ERROR == status || (Z_OK == status && Z->avail_in))
             && ++round < 100);

    if (status == Z_STREAM_END) {
        buffer.data = erealloc(buffer.data, buffer.used + 1);
        buffer.data[buffer.used] = '\0';
        *buf = buffer.data;
        *len = buffer.used;
    } else {
        if (buffer.data) {
            efree(buffer.data);
        }
        status = (status == Z_OK) ? Z_DATA_ERROR : status;
    }

    return status;
}

static inline int
php_zopfli_decode(const char *in, size_t in_size, size_t max_size,
                  int in_type, char **out, size_t *out_size TSRMLS_DC)
{
    int status = Z_DATA_ERROR;
    z_stream Z;

    memset(&Z, 0, sizeof(z_stream));
    Z.zalloc = php_zlib_alloc;
    Z.zfree = php_zlib_free;

    if (in_size) {
retry_raw_inflate:
        status = inflateInit2(&Z, in_type);
        if (status == Z_OK) {
            Z.next_in = (Bytef *)in;
            Z.avail_in = in_size + 1;
            status = php_zopfli_inflate_rounds(&Z, max_size, out, out_size);
            switch (status) {
                case Z_STREAM_END:
                    inflateEnd(&Z);
                    return SUCCESS;
                case Z_DATA_ERROR:
                    if (PHP_ZLIB_ENCODING_ANY == in_type) {
                        inflateEnd(&Z);
                        in_type = PHP_ZLIB_ENCODING_RAW;
                        goto retry_raw_inflate;
                    }
            }
            inflateEnd(&Z);
        }
    }

    *out = NULL;
    *out_size = 0;

    php_error_docref(NULL TSRMLS_CC, E_WARNING, "%s", zError(status));

    return FAILURE;
}

#define PHP_ZOPFLI_DECODE_FUNC(_name, _type, _param_type) \
static ZEND_FUNCTION(_name) \
{ \
    long max_size = 0; \
    char *in, *out = NULL; \
    int in_size; \
    size_t out_size = 0; \
    int in_type = _type; \
    if (_param_type) { \
        if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "s|ll", &in, &in_size, &max_size, &in_type) == FAILURE) { \
            return; \
        } \
        switch (in_type) { \
            case PHP_ZLIB_ENCODING_GZIP: \
            case PHP_ZLIB_ENCODING_DEFLATE: \
            case PHP_ZLIB_ENCODING_RAW: \
            case PHP_ZLIB_ENCODING_ANY: \
                break; \
            case ZOPFLI_TYPE_GZIP: \
                in_type = PHP_ZLIB_ENCODING_GZIP; \
                break; \
            case ZOPFLI_TYPE_DEFLATE: \
                in_type = PHP_ZLIB_ENCODING_RAW; \
                break; \
            case ZOPFLI_TYPE_ZLIB: \
                in_type = PHP_ZLIB_ENCODING_DEFLATE; \
                break; \
            default: \
                php_error_docref(NULL TSRMLS_CC, E_WARNING, "type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE"); \
                RETURN_FALSE; \
        } \
    } else { \
        if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "s|l", &in, &in_size, &max_size) == FAILURE) { \
            return; \
        } \
    } \
    if (max_size < 0) { \
        php_error_docref(NULL TSRMLS_CC, E_WARNING, "length (%ld) must be greater or equal zero", max_size); \
        RETURN_FALSE; \
    } \
    if (php_zopfli_decode(in, in_size, max_size, in_type, &out, &out_size TSRMLS_CC) != SUCCESS) { \
        RETURN_FALSE; \
    } \
    RETURN_STRINGL(out, out_size, 0); \
}

PHP_ZOPFLI_DECODE_FUNC(zopfli_decode, PHP_ZLIB_ENCODING_GZIP, 1);
PHP_ZOPFLI_DECODE_FUNC(zopfli_uncompress, PHP_ZLIB_ENCODING_DEFLATE, 0);
PHP_ZOPFLI_DECODE_FUNC(zopfli_inflate, PHP_ZLIB_ENCODING_RAW, 0);

#else

#define PHP_ZOPFLI_DECODE_FUNC(_name, _type, _param_type) \
static ZEND_FUNCTION(_name) \
{ \
    long max_size = 0, in_type = _type; \
    char *in; \
    int in_size; \
    zval *retval = NULL, *arg_in, *arg_max, fname; \
    zval **args[2]; \
    if (_param_type) { \
        if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "s|ll", &in, &in_size, &max_size, &in_type) == FAILURE) { \
            return; \
        } \
    } else { \
        if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "s|l", &in, &in_size, &max_size) == FAILURE) { \
            return; \
        } \
    } \
    if (max_size < 0) { \
        php_error_docref(NULL TSRMLS_CC, E_WARNING, "length (%ld) must be greater or equal zero", max_size); \
        RETURN_FALSE; \
    } \
    switch (in_type) { \
        case ZOPFLI_TYPE_GZIP: \
            ZVAL_STRING(&fname, "gzdecode", 0); \
            break; \
        case ZOPFLI_TYPE_DEFLATE: \
            ZVAL_STRING(&fname, "gzinflate", 0); \
            break; \
        case ZOPFLI_TYPE_ZLIB: \
            ZVAL_STRING(&fname, "gzuncompress", 0); \
            break; \
        default: \
            php_error_docref(NULL TSRMLS_CC, E_WARNING, "type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE"); \
            RETURN_FALSE; \
    } \
    args[0] = &arg_in; \
    MAKE_STD_ZVAL(arg_in); \
    ZVAL_STRINGL(arg_in, in, in_size, 1); \
    args[1] = &arg_max; \
    MAKE_STD_ZVAL(arg_max); \
    ZVAL_LONG(arg_max, max_size); \
    if (call_user_function_ex(CG(function_table), NULL, &fname, &retval, 2, args, 0, NULL TSRMLS_CC) == FAILURE || !retval) { \
        zval_ptr_dtor(&arg_in); \
        zval_ptr_dtor(&arg_max); \
        RETURN_FALSE; \
    } \
    zval_ptr_dtor(&arg_in); \
    zval_ptr_dtor(&arg_max); \
    if (EG(exception)) { \
        zval_ptr_dtor(&retval); \
        RETURN_FALSE; \
    } \
    RETURN_ZVAL(retval, 1, 0); \
}

PHP_ZOPFLI_DECODE_FUNC(zopfli_decode, ZOPFLI_TYPE_GZIP, 1);
PHP_ZOPFLI_DECODE_FUNC(zopfli_uncompress, ZOPFLI_TYPE_ZLIB, 0);
PHP_ZOPFLI_DECODE_FUNC(zopfli_inflate, ZOPFLI_TYPE_DEFLATE, 0);

#endif
