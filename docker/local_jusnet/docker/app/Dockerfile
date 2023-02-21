# どんな docker イメージを利用して構築をするか
# FROM : ここではPHPの公式イメージである「php:7.3-apache」というあらかじめPHPとApacheがインストールされているイメージを、
# Docker Hubというサイトからダウンロードしてくるようになっている。
FROM php:7.3-apache

# 設定ファイルをdockerコンテナ内のPHP、Apacheに読み込ませる
# ADD：ローカルのファイルをDockerコンテナ内にコピーする
ADD php.ini /usr/local/etc/php/
ADD 000-default.conf /etc/apache2/sites-enabled/
ADD ports.conf /etc/apache2/

# 自己証明書(SSL)
RUN cd /etc/ssl/

ADD ./self_ssl/server.crt /etc/ssl/self_ssl/
ADD ./self_ssl/server.csr /etc/ssl/self_ssl/
ADD ./self_ssl/server.key /etc/ssl/self_ssl/

# ミドルウェアのインストール
RUN apt-get update \
&& apt-get install -y \
zip \
unzip \
vim \
libxml2-dev \
libzip-dev \
&& apt-get clean \
&& docker-php-ext-install pdo_mysql mysqli soap mbstring zip\
&& a2enmod ssl rewrite \
&& service apache2 restart

