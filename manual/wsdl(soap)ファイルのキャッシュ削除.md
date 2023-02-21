# 1.背景
enterprise.wsdl.xml ファイルを更新した際に、  
「フォームの完了画面が表示されない」「SFに登録できない」という事象があった。

# 2.キャッシュの保管場所

linuxサーバでは、デフォルトでは以下に保存される。  
**・/var/lib/php/wsdlcache/**

# 3.解決方法
### (その1) キャッシュをさせない
以下の形で対応できる。

```sh
# エディタで編集
$ sudo vim /etc/php.ini

# 以下の記載をする
[soap]
soap.wsdl_cache_enabled=0
```

### (その2) キャッシュを削除する
以下の形で対応できる。

```sh
# 移動
$ cd /var/lib/php/wsdlcache/

# 削除
$ sudo rm -fi ./xxxxxxxxxxxxx
```
