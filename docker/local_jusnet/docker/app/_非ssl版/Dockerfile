# どんな docker イメージを利用して構築をするか
# FROM : ここではPHPの公式イメージである「php:7.3-apache」というあらかじめPHPとApacheがインストールされているイメージを、
# Docker Hubというサイトからダウンロードしてくるようになっている。
FROM php:7.3-apache

# 設定ファイルをdockerコンテナ内のPHP、Apacheに読み込ませる
# ADD：ローカルのファイルをDockerコンテナ内にコピーする
ADD php.ini /usr/local/etc/php/
ADD 000-default.conf /etc/apache2/sites-enabled/
ADD ports.conf /etc/apache2/

# ミドルウェアのインストール
RUN apt-get update \
&& apt-get install -y \
git \
zip \
unzip \
vim \
libxml2-dev \
&& apt-get clean \
&& docker-php-ext-install pdo_mysql mysqli soap mbstring \
&& a2enmod rewrite\
&& service apache2 restart

