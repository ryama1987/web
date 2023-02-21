# phpバージョン
FROM mysql:8.0.29-debian

#confファイル
ADD my.cnf /etc/mysql/conf.d/my.cnf

# 日本語設定
RUN apt-get update && apt-get install -y locales
RUN sed -i -E 's/# (ja_JP.UTF-8)/ja_JP.UTF-8/' /etc/locale.gen && locale-gen
ENV LANG ja_JP.UTF-8
