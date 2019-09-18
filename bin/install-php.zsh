#!/usr/bin/env zsh

local __DIR__=$(cd `dirname $0`; pwd)
if [ ! $(id -u) = 0 ];then
	echo "!!! need be root, your id -u is: $(id -u)"
	exit;
fi

if [ ! $1 ];then
	echo "!!! please enter php version as param."
	echo "\n  - example:"
    echo "    ./install 7.3.9"
	exit;
fi

# 定义变量
export php_name="php-$1"

# 用户创建
useradd -m worker

# 环境
sudo apt install -y python-dev python-docutils libpcre++0v5 libpcre3 libpcre3-dev libpcre++-dev git libmhash2 libmcrypt4 libxml2-dev libpng-dev libpng++-dev libmcrypt-dev libmhash-dev alien libcurl4-openssl-dev libssl-dev libssl-ocaml-dev libltdl-dev libltdl7 libblkid-dev libtool uuid-dev uuid libevent-2.1-6 libevent-dev autoconf libbz2-dev qt4-qtconfig autoconf2.13 re2c libxml2 xsltproc docbook-xsl libleveldb-dev libboost-all-dev gperf automake gcc bison flex make curl libsqlite3-dev libmysqlclient-dev mercurial libyaml-dev libgmp-dev libmemcached-dev pkg-config libpq-dev libzip-dev

#postgresql postgresql-contrib postgresql-server-dev-9.5

if [ ! -f "/usr/include/gmp.h" ]; then
    sudo ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/include/gmp.h
fi

# 下载
wget -c http://cn.php.net/get/${php_name}.tar.xz/from/this/mirror -O /tmp/${php_name}.tar.xz
if [ ! -f "/tmp/${php_name}.tar.xz" ]; then
    echo "!!! php package download fail, please try again."
    exit;
fi

# 清理上次数据
rm -rf /tmp/${php_name}

# 解压
tar xJf /tmp/${php_name}.tar.xz -C /tmp
if [ ! -d "/tmp/${php_name}" ]; then
    echo "!!! unpack fail, please download again."
    rm -rf /tmp/${php_name}
    rm -rf /tmp/${php_name}.tar.xz
    exit;
fi

# 编译
cd /tmp/${php_name}
./configure --prefix=/home/worker/local \
--with-mysqli=mysqlnd \
--with-pdo-mysql=mysqlnd \
--with-pdo-pgsql \
--with-zlib-dir \
--with-gd \
--enable-ftp \
--with-iconv \
--with-gettext \
--with-curl \
--enable-sockets \
--with-openssl \
--with-libxml-dir=/usr/lib \
--with-gmp \
--with-mhash \
--with-config-file-path=/home/worker/local/etc/php \
--with-config-file-scan-dir=/home/worker/local/etc/php/conf.d \
--enable-mbstring \
--enable-sysvmsg \
--enable-sysvshm \
--enable-sysvsem \
--enable-exif \
--enable-pcntl \
--enable-fpm \
--enable-opcache \
--enable-maintainer-zts \
--enable-zip \
--enable-bcmath \
--with-libdir=/lib/x86_64-linux-gnu \

####--enable-zts \
####--with-libevent-dir=/usr/lib \
####--with-jpeg-dir=/usr/local/lib/

make
make install

# 复制自身配置
mkdir -p /home/worker/local/etc/php
mkdir -p /home/worker/local/etc/php/conf.d
cp -rf /tmp/${php_name}/php.ini-production /home/worker/local/etc/php/php.ini

# 创建软链增加兼容性
groupadd nobody
ln -sf /home/worker/local/bin/php /usr/bin/
ln -sf /home/worker/local/bin/phpize /usr/bin/
ln -sf /home/worker/local/bin/php-config /usr/bin/
ln -sf /home/worker/local/bin/pecl /usr/bin/
ln -sf /home/worker/local/bin/pear /usr/bin/

# 使用cfm工具代替sed
# 生产环境区别
# display_error Off

cfm set /home/worker/local/etc/php/php.ini \
'default_charset="utf-8"' \
'include_path=".:/home/worker/local/lib/php"' \
'date.timezone="Asia/Shanghai"' \
'short_open_tag=On' \
'error_log=/tmp/php_error.log' \
'display_errors=On' \
'log_errors=On'

cfm enable /home/worker/local/etc/php/php.ini \
default_charset \
include_path \
date.timezone \
short_open_tag \
error_log \
display_errors \
log_errors

cfm show /home/worker/local/etc/php/php.ini \
default_charset \
include_path \
date.timezone \
short_open_tag \
error_log \
display_errors \
log_errors


cp /home/worker/local/etc/php-fpm.conf.default /home/worker/local/etc/php-fpm.conf
cp /home/worker/local/etc/php-fpm.d/www.conf.default /home/worker/local/etc/php-fpm.d/www.conf

cfm set /home/worker/local/etc/php-fpm.d/www.conf \
'user=worker' \
'group=worker' \
'listen.owner=worker' \
'listen.group=worker' \
'listen.mode=0666' \
'listen=/dev/shm/php-fpm.sock'

cfm enable /home/worker/local/etc/php-fpm.d/www.conf \
user \
group \
listen.owner \
listen.group \
listen.mode \
listen

cfm show /home/worker/local/etc/php-fpm.d/www.conf \
user \
group \
listen.owner \
listen.group \
listen.mode \
listen


# 修改权限
chown worker:worker -R /home/worker
