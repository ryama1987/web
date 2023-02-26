#!/bin/sh

##########
#env setting
##########

# バックアップ用フォルダのpathの変数
dirpath='/var/backup'

# バックアップの保存期間の変数（ここでは7日間）
period=7

# バックアップ用ファイルのネーミングルールの変数
filenameprefix="html-backup-"
filedate=`date +%y%m%d`
filename=$filenameprefix$filedate

# htmlのバックアップ
tar -C /var/www/ -cvzf /var/backup/$filename.tgz html

# ファイルの権限変更
chmod 744 $dirpath/$filename.tgz

# ex. /var/backup/mysql-dump/mysql-dump-220531
aws s3 cp $dirpath/$filename.tgz s3://website.htmlbackup.bucket/production/

###########
#rotation
###########

# 以下は、8日目に過去のログを削除する処理
olddate=`date --date="$period days ago" +%y%m%d`
oldfile=$filenameprefix$olddate

rm -f $dirpath/$oldfile.tgz
