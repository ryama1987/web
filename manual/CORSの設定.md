# 1.CORSとは?
以下、参考記事。
- [オリジン間リソース共有 (CORS)](https://developer.mozilla.org/ja/docs/Web/HTTP/CORS)
- [なんとなく CORS がわかる...はもう終わりにする。](https://qiita.com/att55/items/2154a8aad8bf1409db2b)

# 2.実際の対策

## 2.1 OKな例
```sh:.htaccess
<IfModule mod_headers.c>
    SetEnvIfNoCase Origin "http(s)?://(.*)?(developdevelop\.co\.jp|localhost)(:\d+)?$" AccessControlAllowOrigin=$0
    Header set Access-Control-Allow-Origin %{AccessControlAllowOrigin}e env=AccessControlAllowOrigin
</IfModule>
```
```sh
# 以下のドメインが通るようになる。
http://www.developdevelop.co.jp
https://www.developdevelop.co.jp
https://career.developdevelop.co.jp
https://testcareer.developdevelop.co.jp
http://localhost:8080
https://localhost:8080
```

## 2.2 NGな例
```sh
# このケースだと最後だけ有効になるとのこと
Header set Access-Control-Allow-Origin "https://career.developdevelop.co.jp"
Header set Access-Control-Allow-Origin "https://www.developdevelop.co.jp"
Header set Access-Control-Allow-Origin "https://teststaff.developdevelop.co.jp"
```
- [Access-Control-Allow-Originヘッダーで複数のドメインを処理する オリジン間リソース共有(CORS)](https://ao-system.net/note/118)
- [Access-Control-Allow-Originヘッダ関連のエラーがCORSなリクエスト時に出る場合の対処【Apache】](https://www.teradas.net/archives/18779/)
