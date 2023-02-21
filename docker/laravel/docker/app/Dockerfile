# どんな docker イメージを利用して構築をするか
# FROM : ここではPHPの公式イメージである「php:7.3-apache」というあらかじめPHPとApacheがインストールされているイメージを、
# Docker Hubというサイトからダウンロードしてくるようになっている。
FROM php:7.3-apache

# 設定ファイルをdockerコンテナ内のPHP、Apacheに読み込ませる
# ADD：ローカルのファイルをDockerコンテナ内にコピーする
ADD php.ini /usr/local/etc/php/
ADD 000-default.conf /etc/apache2/sites-enabled/

#自己証明書(SSL)
ADD ./self_ssl/server.crt /etc/ssl/
ADD ./self_ssl/server.csr /etc/ssl/
ADD ./self_ssl/server.key /etc/ssl/

# Composerのインストール
# RUN：コンテナ内でコマンド実行する
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# ミドルウェアのインストール
RUN apt-get update \
&& apt-get install -y \
libxml2-dev \
git \
zip \
unzip \
vim \
libpng-dev \
libpq-dev \
libzip-dev \
&& docker-php-ext-install pdo_mysql \
mbstring \
soap \
zip

# SSL有効化
RUN a2enmod ssl

# Laravelで必要になるmodRewriteを有効化する
RUN mv /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled
RUN /bin/sh -c a2enmod rewrite


