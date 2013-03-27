
#ifdef HAVE_CONFIG_H
#    include "config.h"
#endif

#include "php.h"
#include "php_zopfli.h"

#ifdef HAVE_ZLIB_H

#include "png.h"

static void php_zopfli_write_idat_chunk(unsigned char *out, uint32_t *opos,
                                        unsigned char *idat_chunk, size_t idat_chunk_size)
{
    uLong crc;

    // copy IDAT chunk length
    php_zopfli_write_uint32(out, opos, (uint32_t)idat_chunk_size);

    // copy IDAT chunk type
    strncpy(((char *)out + *opos), "IDAT", sizeof("IDAT") - 1);
    *opos += 4;

    // copy idat chunk data
    memcpy(out + *opos, idat_chunk, idat_chunk_size);
    *opos += idat_chunk_size;

    // copy idat chunk crc
    crc = crc32(0L, (Bytef *)"IDAT", sizeof("IDAT") - 1);
    crc = crc32(crc, idat_chunk, idat_chunk_size);
    php_zopfli_write_uint32(out, opos, (uint32_t)crc);
}

int php_zopfli_is_invalid_signature(unsigned char *in)
{
    if (strncmp((char *)in, "\x89\x50\x4e\x47\xd\xa\x1a\xa", ZOPFLI_PNG_SIGNATURE_SIZE) != 0) {
        return SUCCESS;
    }
    return FAILURE;
}

uint32_t php_zopfli_read_uint32(unsigned char *in, uint32_t *ipos)
{
    int endian_little = 1;
    uint32_t result;
    if (*((uint8_t *)&endian_little) == 1) {
        result = 
            (in[*ipos]     << 24) | (in[(*ipos)+1] << 16) |
            (in[(*ipos)+2] << 8)  | (in[(*ipos)+3]);
    } else {
        result = *((uint32_t *)&in[*ipos]);
    }
    *ipos += 4;
    return result;
}

void php_zopfli_write_uint32(unsigned char *out, uint32_t *opos, uint32_t data)
{
    int endian_little = 1;
    /* uint32_t tmp; */
    if (*((uint8_t *)&endian_little) == 1) {
        out[*opos]     = data >> 24;
        out[(*opos+1)] = data >> 16;
        out[(*opos+2)] = data >> 8;
        out[(*opos+3)] = data;
    } else {
        out[*opos] = data;
    }
    *opos += 4;
}

uLongf php_zopfli_calc_inflate_buf_size(unsigned char *in, uint32_t *ipos)
{
    uint32_t width;
    uint32_t height;
    uint8_t  depth;
    uint8_t  ctype;
    /*
    uint8_t  compress;
    uint8_t  filter;
    uint8_t  interlace;
    */
    int      bit_depth;
    int      alpha;
    width     = php_zopfli_read_uint32(in, ipos);
    height    = php_zopfli_read_uint32(in, ipos);
    depth     = in[*ipos];
    ctype     = in[*ipos + 1];
    /*
    compress  = in[*ipos + 2];
    filter    = in[*ipos + 3];
    interlace = in[*ipos + 4];
    */
    *ipos += 5;

    bit_depth = depth == 16 ? 2 : 1;
    alpha     = (ctype & 0x4) == 0 ? 3 : 4;
    return width * height * bit_depth * alpha + height;
}

void php_zopfli_write_idat_chunks(unsigned char *out, uint32_t *opos,
                                  unsigned char *idat_chunks, size_t idat_chunks_size,
                                  size_t idat_chunk_size)
{
    int i = 0;
    while (idat_chunks_size > 0) {
        if (idat_chunks_size < idat_chunk_size) {
            php_zopfli_write_idat_chunk(out, opos, idat_chunks + (i * idat_chunk_size), idat_chunks_size);
            idat_chunks_size = 0;
        } else {
            php_zopfli_write_idat_chunk(out, opos, idat_chunks + (i * idat_chunk_size), idat_chunk_size);
            idat_chunks_size -= idat_chunk_size;
        }
        i++;
    }
}

#endif
