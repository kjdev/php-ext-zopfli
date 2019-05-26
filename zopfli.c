
#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "php_zopfli.h"

#ifdef ZTS
#    include "TSRM.h"
#endif

#ifdef HAVE_ZLIB_H
#include "png.h"
#endif

/* zopfli */
#include "zopfli/src/zopfli/deflate.h"
#include "zopfli/src/zopfli/gzip_container.h"
#include "zopfli/src/zopfli/zlib_container.h"

static ZEND_FUNCTION(zopfli_encode);
static ZEND_FUNCTION(zopfli_compress);
static ZEND_FUNCTION(zopfli_deflate);
static ZEND_FUNCTION(zopfli_decode);
static ZEND_FUNCTION(zopfli_uncompress);
static ZEND_FUNCTION(zopfli_inflate);
#ifdef HAVE_ZLIB_H
static ZEND_FUNCTION(zopfli_png_recompress);
#endif

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

#ifdef HAVE_ZLIB_H
ZEND_BEGIN_ARG_INFO_EX(arginfo_zopfli_png_recompress, 0, 0, 1)
    ZEND_ARG_INFO(0, data)
    ZEND_ARG_INFO(0, iteration)
ZEND_END_ARG_INFO()
#endif

static zend_function_entry zopfli_functions[] = {
    ZEND_FE(zopfli_encode, arginfo_zopfli_encode)
    ZEND_FE(zopfli_compress, arginfo_zopfli_compress)
    ZEND_FE(zopfli_deflate, arginfo_zopfli_deflate)
    ZEND_FE(zopfli_decode, arginfo_zopfli_decode)
    ZEND_FE(zopfli_uncompress, arginfo_zopfli_uncompress)
    ZEND_FE(zopfli_inflate, arginfo_zopfli_inflate)
#ifdef HAVE_ZLIB_H
    ZEND_FE(zopfli_png_recompress, arginfo_zopfli_png_recompress)
#endif
    ZEND_FE_END
};

static ZEND_MINIT_FUNCTION(zopfli)
{
    REGISTER_LONG_CONSTANT("ZOPFLI_GZIP", ZOPFLI_TYPE_GZIP,
                           CONST_CS | CONST_PERSISTENT);
    REGISTER_LONG_CONSTANT("ZOPFLI_ZLIB", ZOPFLI_TYPE_ZLIB,
                           CONST_CS | CONST_PERSISTENT);
    REGISTER_LONG_CONSTANT("ZOPFLI_DEFLATE", ZOPFLI_TYPE_DEFLATE,
                           CONST_CS | CONST_PERSISTENT);
    return SUCCESS;
}

static ZEND_MINFO_FUNCTION(zopfli)
{
    php_info_print_table_start();
    php_info_print_table_row(2, "Zopfli support", "enabled");
    php_info_print_table_row(2, "Extension Version", ZOPFLI_EXT_VERSION);
#ifdef HAVE_ZLIB_H
    php_info_print_table_row(2, "Zopfli png recompress", "supported");
#else
    php_info_print_table_row(2, "Zopfli png recompress", "not supported");
#endif
    php_info_print_table_end();
}

zend_module_entry zopfli_module_entry = {
    STANDARD_MODULE_HEADER,
    "zopfli",
    zopfli_functions,
    ZEND_MINIT(zopfli),
    NULL,
    NULL,
    NULL,
    ZEND_MINFO(zopfli),
    ZOPFLI_EXT_VERSION,
    STANDARD_MODULE_PROPERTIES
};

#ifdef COMPILE_DL_ZOPFLI
ZEND_GET_MODULE(zopfli)
#endif

static inline int
php_zopfli_encode(unsigned char *in, size_t in_size, int iteration,
                  unsigned int out_type, unsigned char **out, size_t *out_size)
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

#ifdef HAVE_ZLIB_H

static inline int
php_zopfli_png_recompress(unsigned char *in, size_t in_size, int iteration,
                          unsigned char **out, size_t *out_size)
{
    ZopfliOptions options;
    uint32_t ipos;
    uint32_t opos;
    uint32_t chunk_len;
    uLongf   idat_pos;
    unsigned char *idat_buf;
    unsigned char *inflate_buf = NULL;
    unsigned char *compressed_buf = NULL;
    uLongf inflate_buf_size;
    size_t compressed_buf_size;
    uLong crc;
    int result;
    int idat_chunk_idx;
    size_t idat_chunk_size;

    ZopfliInitOptions(&options);
    options.numiterations = iteration;

    ipos      = 0;
    opos      = 0;
    idat_pos  = 0;
    *out      = NULL;
    *out_size = 0;
    inflate_buf_size = 0;
    compressed_buf_size = 0;
    idat_chunk_size = 0;
    idat_chunk_idx = 0;

    if (php_zopfli_is_invalid_signature(in) == SUCCESS) {
        return FAILURE;
    }
    idat_buf       = emalloc(in_size);
    inflate_buf    = NULL;
    compressed_buf = NULL;
    *out           = emalloc(in_size * 2);
    memcpy(*out + opos, &in[ipos], ZOPFLI_PNG_SIGNATURE_SIZE);
    ipos += ZOPFLI_PNG_SIGNATURE_SIZE;
    opos += ZOPFLI_PNG_SIGNATURE_SIZE;

    do {
        chunk_len = php_zopfli_read_uint32(in, &ipos);
        if (strncmp((char *)&in[ipos], "IHDR", sizeof("IHDR") - 1) == 0) {
            // copy IHDR chunk
            memcpy(*out + opos, &in[ipos - 4], ZOPFLI_PNG_IHDR_SIZE);

            // skip chunk type
            ipos += 4;

            inflate_buf_size = php_zopfli_calc_inflate_buf_size(in, &ipos);

            ipos += 4; // skip crc
            opos += ZOPFLI_PNG_IHDR_SIZE;
        } else if (strncmp((char *)&in[ipos], "IDAT", sizeof("IDAT") - 1) == 0) {
            // skip chunk type
            ipos += 4;

            memcpy(&idat_buf[idat_pos], &in[ipos], chunk_len);

            // skip crc
            ipos += chunk_len + 4;

            idat_pos += chunk_len;
            if (idat_chunk_idx == 0) {
                idat_chunk_size = chunk_len;
                idat_chunk_idx++;
            }
        } else if (strncmp((char *)&in[ipos], "IEND", sizeof("IEND") - 1) == 0) {
            inflate_buf = emalloc(inflate_buf_size);
            if ((result = uncompress(inflate_buf, &inflate_buf_size, idat_buf, idat_pos)) != Z_OK) {
                if (idat_buf != NULL) {
                    efree(idat_buf);
                }
                if (inflate_buf != NULL) {
                    efree(inflate_buf);
                }
                return FAILURE;
            }

            // recompress
            compressed_buf = malloc(inflate_buf_size * 2);
            ZopfliZlibCompress(&options, inflate_buf, inflate_buf_size, &compressed_buf, &compressed_buf_size);

            // copy IDAT chunks
            php_zopfli_write_idat_chunks(*out, &opos, 
                                         compressed_buf, compressed_buf_size,
                                         idat_chunk_size);

            // copy IEND chunk
            memcpy(*out + opos, &in[ipos - 4], chunk_len + 12);
            ipos += chunk_len + 8;
            opos += chunk_len + 12;
        } else {
            memcpy(*out + opos, &in[ipos - 4], chunk_len + 12);
            ipos += chunk_len + 8;
            opos += chunk_len + 12;
        }
    } while(ipos < in_size);

    if (idat_buf != NULL) {
        efree(idat_buf);
    }
    if (inflate_buf != NULL) {
        efree(inflate_buf);
    }

    *out_size = opos;

    return SUCCESS;
}

#endif

static inline void php_zopfli_encode_func(INTERNAL_FUNCTION_PARAMETERS, zend_long out_type) {
    zend_long iteration = 15;
    char *in;
    char *out = NULL;
    size_t in_size;
    size_t out_size = 0;

    ZEND_PARSE_PARAMETERS_START(1, 3)
        Z_PARAM_STRING(in, in_size)
        Z_PARAM_OPTIONAL
        Z_PARAM_LONG(iteration)
        Z_PARAM_LONG(out_type)
    ZEND_PARSE_PARAMETERS_END();

    if (iteration <= 0) {
        php_error_docref(NULL, E_WARNING, "compression iterations (%ld) must be greater than 0", iteration);
        RETURN_FALSE;
    }
    switch (out_type) {
        case ZOPFLI_TYPE_GZIP:
        case ZOPFLI_TYPE_ZLIB:
        case ZOPFLI_TYPE_DEFLATE:
            break;
        default:
            php_error_docref(NULL, E_WARNING, "type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE");
            RETURN_FALSE;
    }
    if (php_zopfli_encode((unsigned char *)in, in_size, iteration, out_type, (unsigned char **)&out, &out_size) != SUCCESS) {
        RETURN_FALSE;
    }
    RETVAL_STRINGL(out, out_size);
    free(out);
}

static ZEND_FUNCTION(zopfli_encode)
{
    php_zopfli_encode_func(INTERNAL_FUNCTION_PARAM_PASSTHRU, ZOPFLI_TYPE_GZIP);
}

static ZEND_FUNCTION(zopfli_compress)
{
    php_zopfli_encode_func(INTERNAL_FUNCTION_PARAM_PASSTHRU, ZOPFLI_TYPE_ZLIB);
}

static ZEND_FUNCTION(zopfli_deflate)
{
    php_zopfli_encode_func(INTERNAL_FUNCTION_PARAM_PASSTHRU, ZOPFLI_TYPE_DEFLATE);
}

#ifdef HAVE_ZLIB_H
static ZEND_FUNCTION(zopfli_png_recompress)
{
    zend_long iteration = 15;
    char *in;
    char *out = NULL;
    size_t in_size = 0;
    size_t out_size = 0;

	ZEND_PARSE_PARAMETERS_START(1, 2)
		Z_PARAM_STRING(in, in_size)
		Z_PARAM_OPTIONAL
		Z_PARAM_LONG(iteration)
	ZEND_PARSE_PARAMETERS_END();

    if (iteration <= 0) {
        php_error_docref(NULL, E_WARNING, "compression iterations (%ld) must be greater than 0", iteration);
        RETURN_FALSE;
    }
    if (php_zopfli_png_recompress((unsigned char *)in, in_size, iteration, (unsigned char **)&out, &out_size) != SUCCESS) {
        php_error_docref(NULL, E_WARNING, "Invalid PNG Image");
        RETURN_FALSE;
    }
    RETVAL_STRINGL(out, out_size);
    efree(out);
}
#endif

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
                  int in_type, char **out, size_t *out_size)
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

    php_error_docref(NULL, E_WARNING, "%s", zError(status));

    return FAILURE;
}

static inline void php_zopfli_decode_func(INTERNAL_FUNCTION_PARAMETERS, zend_long in_type, zend_long param_type)
{
    zend_long max_size = 0;
    char *in;
    char *out = NULL;
    size_t in_size;
    size_t out_size = 0;
    if (param_type) {
        ZEND_PARSE_PARAMETERS_START(1, 3)
            Z_PARAM_STRING(in, in_size)
            Z_PARAM_OPTIONAL
            Z_PARAM_LONG(max_size)
            Z_PARAM_LONG(in_type)
        ZEND_PARSE_PARAMETERS_END();

        switch (in_type) {
            case PHP_ZLIB_ENCODING_GZIP:
            case PHP_ZLIB_ENCODING_DEFLATE:
            case PHP_ZLIB_ENCODING_RAW:
            case PHP_ZLIB_ENCODING_ANY:
                break;
            case ZOPFLI_TYPE_GZIP:
                in_type = PHP_ZLIB_ENCODING_GZIP;
                break;
            case ZOPFLI_TYPE_DEFLATE:
                in_type = PHP_ZLIB_ENCODING_RAW;
                break;
            case ZOPFLI_TYPE_ZLIB:
                in_type = PHP_ZLIB_ENCODING_DEFLATE;
                break;
            default:
                php_error_docref(NULL, E_WARNING, "type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE");
                RETURN_FALSE;
        }
    } else {
        ZEND_PARSE_PARAMETERS_START(1, 2)
            Z_PARAM_STRING(in, in_size)
            Z_PARAM_OPTIONAL
            Z_PARAM_LONG(max_size)
        ZEND_PARSE_PARAMETERS_END();
    }
    if (max_size < 0) {
        php_error_docref(NULL, E_WARNING, "length (%ld) must be greater or equal zero", max_size);
        RETURN_FALSE;
    }
    if (php_zopfli_decode(in, in_size, max_size, in_type, &out, &out_size) != SUCCESS) {
        RETURN_FALSE;
    }
    RETVAL_STRINGL(out, out_size);
    efree(out);
}

static ZEND_FUNCTION(zopfli_decode)
{
    php_zopfli_decode_func(INTERNAL_FUNCTION_PARAM_PASSTHRU, PHP_ZLIB_ENCODING_GZIP, 1);
}

static ZEND_FUNCTION(zopfli_uncompress)
{
    php_zopfli_decode_func(INTERNAL_FUNCTION_PARAM_PASSTHRU, PHP_ZLIB_ENCODING_DEFLATE, 0);
}

static ZEND_FUNCTION(zopfli_inflate)
{
    php_zopfli_decode_func(INTERNAL_FUNCTION_PARAM_PASSTHRU, PHP_ZLIB_ENCODING_RAW, 0);
}

#else

static inline void php_zopfli_decode_func(INTERNAL_FUNCTION_PARAMETERS, zend_long in_type, zend_long param_type)
{
    zend_long max_size = 0;
    char *in;
    size_t in_size;
    zval args[2], retval, fname;
    if (param_type) {
        ZEND_PARSE_PARAMETERS_START(1, 3)
            Z_PARAM_STRING(in, in_size)
            Z_PARAM_OPTIONAL
            Z_PARAM_LONG(max_size)
            Z_PARAM_LONG(in_type)
        ZEND_PARSE_PARAMETERS_END();
    } else {
        ZEND_PARSE_PARAMETERS_START(1, 2)
            Z_PARAM_STRING(in, in_size)
            Z_PARAM_OPTIONAL
            Z_PARAM_LONG(max_size)
        ZEND_PARSE_PARAMETERS_END();
    }
    if (max_size < 0) {
        php_error_docref(NULL, E_WARNING, "length (%ld) must be greater or equal zero", max_size);
        RETURN_FALSE;
    }
    switch (in_type) {
        case ZOPFLI_TYPE_GZIP:
            ZVAL_STRING(&fname, "gzdecode");
            break;
        case ZOPFLI_TYPE_DEFLATE:
            ZVAL_STRING(&fname, "gzinflate");
            break;
        case ZOPFLI_TYPE_ZLIB:
            ZVAL_STRING(&fname, "gzuncompress");
            break;
        default:
            php_error_docref(NULL, E_WARNING, "type mode must be either ZOPFLI_GZIP, ZOPFLI_ZLIB or ZOPFLI_DEFLATE");
            RETURN_FALSE;
    }

    ZVAL_STRINGL(&args[0], in, in_size);
    ZVAL_LONG(&args[1], max_size);
    if (call_user_function_ex(CG(function_table), NULL, &fname, &retval, 2, args, 0, NULL) == FAILURE) {
        zval_ptr_dtor(&args[0]);
        zval_ptr_dtor(&args[1]);
        zval_ptr_dtor(&fname);
        RETURN_FALSE;
    }
    zval_ptr_dtor(&args[0]);
    zval_ptr_dtor(&args[1]);
    zval_ptr_dtor(&fname);
    if (EG(exception)) {
        zval_ptr_dtor(&retval);
        RETURN_FALSE;
    }
    RETURN_ZVAL(&retval, 1, 0);
}

static ZEND_FUNCTION(zopfli_decode)
{
    php_zopfli_decode_func(INTERNAL_FUNCTION_PARAM_PASSTHRU, ZOPFLI_TYPE_GZIP, 1);
}

static ZEND_FUNCTION(zopfli_uncompress)
{
    php_zopfli_decode_func(INTERNAL_FUNCTION_PARAM_PASSTHRU, ZOPFLI_TYPE_ZLIB, 0);
}

static ZEND_FUNCTION(zopfli_inflate)
{
    php_zopfli_decode_func(INTERNAL_FUNCTION_PARAM_PASSTHRU, ZOPFLI_TYPE_DEFLATE, 0);
}

#endif
