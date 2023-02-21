FROM php:7.3-apache

# 設定ファイルをdockerコンテナ内のPHP、Apacheに読み込ませる
# ADD：ローカルのファイルをDockerコンテナ内にコピーする
ADD php.ini /usr/local/etc/php/
ADD 000-default.conf /etc/apache2/sites-enabled/

# ミドルウェアのインストール
# libdi-perl、libdbd-mysql-perlはMovableType用
RUN apt-get update \
&& apt-get install -y \
git \
zip \
unzip \
vim \
libxml2-dev \
libapache2-mod-perl2 \
libdbi-perl \
libdbd-mysql-perl \
libgd-perl \
&& apt-get clean \
&& docker-php-ext-install pdo_mysql mysqli soap mbstring \
&& a2enmod rewrite\
&& service apache2 restart
