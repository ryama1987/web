git導入例です。  
※他の場合も同じような手順になります。

## gitのマージ手順
### (1)はじめてgithubからマージする場合。

```sh
cd /var/www/html/xxxxx

# 新規でローカルリポジトリの作成
git init

# リモートリポジトリを登録する
git remote add origin https://github.com/YYYYY/reporeporepo

# 登録されたリポジトリを確認(※ -aは、ローカル・リポート含めた全て表示のオプション)
git branch -a
## remote/origin/main と表示されるはず。

# リモートリポジトリから情報を持ってくる。
git fetch origin main

# リモートリポジトリの情報を上書きする
git reset --hard origin/main

# もう一度リポジトリ全体を確認
git branch -a

#以下のように表示されるはず。
## *master 
## remote/origin/main 

# ローカルリポジトリのブランチをmasterからmainに修正
git branch -m master main

# 最後にHEADの設定を行う。
git remote set-head origin main

# 最後にリポジトリ全体を確認
git branch -a

## 以下のように表示される
## * main ← ローカルリポジトリ
##  remotes/origin/HEAD -> origin/main ← リモートのHEAD
##  remotes/origin/main ← リモートリポジトリ
```

>「git remote add origin リモートリポジトリの場所」で、現在のローカルリポジトリに指定したリモートリポジトリを追加します。「origin」はデフォルトで使われる名前です（別の名前を指定可能）。
>
>リモートリポジトリの場所を指定するには、ディレクトリ名を使うか、GitHubなどのWebサービスの場合は「https://github.com/ユーザー名/リポジトリ名」のようなURLを使います。いずれの場合も、既に存在するリポジトリを指定する必要があります。
>
### (2)2回目以降、githubからマージする場合。

※githubのユーザー名とトークンの入力必要  
※リモートリポジトリoriginのmainブランチをローカルリポジトリに同期の例  

```sh
# fetchで情報を取得
# この時、ユーザーIDとアクセストークンを求められるので入力
git fetch origin main

#必要があれば、差分を確認
git diff HEAD..origin/main

#問題なければマージする
git merge origin/main

or

#特に確認が必要なく反映させる場合はpullを使う
#ユーザーIDとアクセストークンを求められるので入力
git pull origin main
```
### その他コマンド

```sh
#ローカルブランチ削除
git branch -d ブランチ名

#ローカルに登録してあるリモートブランチ削除
git branch -rd origin/ブランチ名

```

### SSH接続となっているgitへの接続コマンド
develop-xxxxxx.yyyy.co.jp　や　staging-xxxxxx.yyyy.co.jp　のサーバは、  
github actionsを使用しているので、SSH接続となっている。  
もしgithub actionsが上手くいかない場合は、以下のコマンドで操作する必要がある。

```sh
#SSH接続チェック
ssh -T git@github.com.xxxxx

#SSH接続でpullする場合
# git@github.com.(SSHのconfigで指定した名称):(github組織名)/(リポジトリ名).git
git pull git@github.com.xxxxx:orgrgorg/reporeporepo.git
```
