# 1. Async / Await　参考関連記事

- [awaitでfetchを書く、promiseでfetchを書く](https://zenn.dev/kawaxumax/articles/0044a0e30536e2)
- [return と return await の3つの違い](https://zenn.dev/azukiazusa/articles/difference-between-return-and-return-await)
- [Async / Awaitを使う時、Asyncファンクションは必ずPromiseが返る](https://zenn.dev/nana/articles/ff65486fcd0e34)
- [【初学】初めてのAPI（fetchとかpromiseとか良くわからない)](https://qiita.com/irico/items/7267e8983434884cafe2)

# 2. fetchで返ってくる値がpromiseで、その後の処理方法が分からない場合
- [javascriptでpromiseの最終結果を変数へ格納する方法](https://ja.stackoverflow.com/questions/66311/javascript%E3%81%A7promise%E3%81%AE%E6%9C%80%E7%B5%82%E7%B5%90%E6%9E%9C%E3%82%92%E5%A4%89%E6%95%B0%E3%81%B8%E6%A0%BC%E7%B4%8D%E3%81%99%E3%82%8B%E6%96%B9%E6%B3%95)
>JavaScriptの同期処理と非同期処理の理解が追いついていないかと思うので、実装の流れに混乱が生じていると考えられます。まずは（おそらく動くであろう）書き直したコードはこちらです。
> ```javascript
> async function get_list() {
>   // get_listの実行後のthenの第一引数で取得することができる
>   return await eel.projectlist()();
> }
> 
> window.onload = function () {
>   get_list().then(function (gli) {
>     var txt = "";
>     for (let i = 0; i < gli.length; i++) {
>       txt =
>         txt +
>         `<div class="col-lg-3 col-md-6 col-sm-6 work"> <a href="images/work-8.jpg" 　class="work-box"> <img src="images/work-8.jpg">
>                     <div class="overlay">
>                       <div class="overlay-caption">
>                         <h5>Project Name</h5>
>                         <p>${gli[i]}</p>
>                       </div>
>                     </div>
>                     <!-- overlay --> 
>                     </a> </div>`;
>     }
>     document.getElementById("message").innerHTML = txt;
>   });
> };
> ```
> Promiseから値を取得するような場合、Promiseチェーンを繋げる必要があります。今回の質問では、get_list関数がPromiseを返すので、値を取得するためには、thenを繋げ、callback中で値を操作する必要があります。
> ```javascript
> get_list().then(function (value) {
>   console.log(value); // Promise Chainで値を取得できる。
> })
> ```
> 次に、Promiseは非同期で処理されるため、コードを書いた順番で処理されるとは限りません。
> ```javascript
> get_list().then(function () {
>   console.log("hello from promise chain!");
> })
> console.log("hello from out of promise chain!")
> ```
> これを実行すると、ログには以下の順番で出てくると思います。
> ```javascript
> "hello from out of promise chain!"
> "hello from promise chain!"
> ```
> 非同期処理はJavaScriptのruntimeによって制御されるため、実際に上記の順序出てくるかもしれないし、出てこないかもしれません。これは正確にはわかりません。ただ明らかなのは、Promise ChainのCallback中に処理を書くことで非同期処理の完了を確実に取得することが可能です。
> 
> さて、ここまでの回答で不明な点があればもう少しPromiseについて学習してみてください。
> https://azu.github.io/promises-book/




