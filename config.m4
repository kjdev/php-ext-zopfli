dnl config.m4 for extension zopfli

dnl Check PHP version:
AC_MSG_CHECKING(PHP version)
if test ! -z "$phpincludedir"; then
    PHP_VERSION=`grep 'PHP_VERSION ' $phpincludedir/main/php_version.h | sed -e 's/.*"\([[0-9\.]]*\)".*/\1/g' 2>/dev/null`
elif test ! -z "$PHP_CONFIG"; then
    PHP_VERSION=`$PHP_CONFIG --version 2>/dev/null`
fi

if test x"$PHP_VERSION" = "x"; then
    AC_MSG_WARN([none])
else
    PHP_MAJOR_VERSION=`echo $PHP_VERSION | sed -e 's/\([[0-9]]*\)\.\([[0-9]]*\)\.\([[0-9]]*\).*/\1/g' 2>/dev/null`
    PHP_MINOR_VERSION=`echo $PHP_VERSION | sed -e 's/\([[0-9]]*\)\.\([[0-9]]*\)\.\([[0-9]]*\).*/\2/g' 2>/dev/null`
    PHP_RELEASE_VERSION=`echo $PHP_VERSION | sed -e 's/\([[0-9]]*\)\.\([[0-9]]*\)\.\([[0-9]]*\).*/\3/g' 2>/dev/null`
    AC_MSG_RESULT([$PHP_VERSION])
fi

if test $PHP_MAJOR_VERSION -lt 5; then
   AC_MSG_ERROR([need at least PHP 5 or newer])
fi

PHP_ARG_ENABLE(zopfli, whether to enable zopfli support,
[  --enable-zopfli         Enable zopfli support])

if test "$PHP_ZOPFLI" != "no"; then

    PHP_ARG_ENABLE(zopfli-zlib, whether to enable zlib,
    [  --disable-zopfli-zlib   Disable zlib], yes, no)

    if test "$PHP_ZOPFLI_ZLIB" != "no"; then
        SEARCH_PATH="/usr/local /usr"
        SEARCH_FOR="/include/zlib.h"
        if test -r \$PHP_$EXTNAME/\$SEARCH_FOR; then
            ${EXTNAME}_DIR=\$PHP_$EXTNAME
        else
            AC_MSG_CHECKING([for zlib.h files in default path])
            for i in \$SEARCH_PATH ; do
                if test -r \$i/\$SEARCH_FOR; then
                    ${EXTNAME}_DIR=\$i
                   AC_MSG_RESULT(found in \$i)
                fi
            done
        fi

        if test -z "\$${EXTNAME}_DIR"; then
            AC_MSG_RESULT([not found])
            AC_MSG_ERROR([Please reinstall the zlib.h distribution or configure option to --disable-zopfli-zlib])
        fi

        PHP_DEF_HAVE(ZLIB_H)
    fi

    PHP_NEW_EXTENSION(zopfli, zopfli.c png.c zopfli/src/zopfli/blocksplitter.c zopfli/src/zopfli/hash.c zopfli/src/zopfli/tree.c zopfli/src/zopfli/cache.c zopfli/src/zopfli/katajainen.c zopfli/src/zopfli/util.c zopfli/src/zopfli/deflate.c zopfli/src/zopfli/lz77.c zopfli/src/zopfli/zlib_container.c zopfli/src/zopfli/gzip_container.c zopfli/src/zopfli/squeeze.c, $ext_shared)

fi
