# phpバージョン
FROM php:8.1-alpine

#apacheの設定追加
ADD httpd.conf /etc/apache2/
ADD vhost.conf /etc/apache2/conf.d/

#phpの設定
#ADD php.ini /etc/php81/

#ネットワークの設定
ADD interfaces /etc/network/

#composerのインストール
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# アップデートとgitのインストール！！
RUN apk add --update && apk add openrc \
git \
zip \
vim \
oniguruma-dev \
libxml2-dev \
apache2 \
apache2-proxy \
apache2-ssl \
apache2-utils \
openssl \
logrotate \
php-apache2 \
php-session \
php-fpm \
php-openssl \
php-tokenizer \
php-pdo \
php-pdo_dblib \
php-pdo_mysql \
php-mbstring \
php-soap

# alpineでのapache2の起動
RUN rc-update add apache2 && mkdir /run/openrc && touch /run/openrc/softlevel
RUN rc-status
#RUN sed -i 's/#rc_sys=""/rc_sys="lxc"/g' /etc/rc.conf
#RUN echo 'rc_provide="loopback net"' >> /etc/rc.conf
#RUN sed -i 's/^#\(rc_logger="YES"\)$/\1/' /etc/rc.conf
#RUN sed -i '/tty/d' /etc/inittab 
RUN sed -i 's/hostname $opts/# hostname $opts/g' /etc/init.d/hostname
#RUN sed -i 's/mount -t tmpfs/# mount -t tmpfs/g' /lib/rc/sh/init.sh 
RUN sed -i 's/cgroup_add_service /# cgroup_add_service /g' /lib/rc/sh/openrc-run.sh

