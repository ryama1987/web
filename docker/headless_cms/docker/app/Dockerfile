# どんな docker イメージを利用して構築をするか
# FROM : ここではPHPの公式イメージである「php:7.3-apache」というあらかじめPHPとApacheがインストールされているイメージを、
# Docker Hubというサイトからダウンロードしてくるようになっている。
FROM php:8.1-apache

# ミドルウェアのインストール
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
&& apt-get update \
&& apt-get install -y \
git \
zip \
unzip \
vim \
libxml2-dev \
libonig-dev \
nodejs \
&& apt-get clean \
&& docker-php-ext-install pdo_mysql mysqli soap \
&& a2enmod rewrite \
&& service apache2 restart

