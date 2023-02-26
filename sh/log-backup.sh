#!/bin/sh

##########
#env setting
##########

# バックアップ用フォルダのpathの変数
dirpath='/var/backup'

# バックアップ用ファイルのネーミングルールの変数
filenameprefix="log-backup-"
filedate=`date +%y%m%d`
filename=$filenameprefix$filedate

# httpd log のバックアップ
#tar -C /var -cvzf $dirpath/$filename.tgz log
tar -C /var/log -cvzf $dirpath/$filename.tgz httpd

# ファイルの権限変更
chmod 744 $dirpath/$filename.tgz

# ex. /var/backup/mysql-dump/mysql-dump-220531
aws s3 cp $dirpath/$filename.tgz s3://website.log.archive/production/

# s3にアップ後圧縮ファイルを削除する
rm -f $dirpath/$filename.tgz
