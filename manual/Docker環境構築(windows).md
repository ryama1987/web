# 参照元  
http://sys-manual.jusnet.co.jp/Web%E9%96%8B%E7%99%BA/Docker/%E9%96%8B%E7%99%BA%E7%92%B0%E5%A2%83%E3%81%AE%E6%A7%8B%E7%AF%89(Laravel) 

# 0.前提
GitHub Desktopを使ったケースでの設定になります。  
※C:\Users\\[ユーザ名]\Documents\GitHub\　にデータがあることが前提です。

# 1. 必要ソフトの事前準備 

## 1.1 WSL2のインストール
- WSL2（Windows Subsystem for Linux）をインストールする必要があるので、事前にインストールしておく。  
  [WSL2のインストールと分かりやすく解説【Windows10/11】](https://chigusa-web.com/blog/wsl2-win11/)

## 1.2 Docker Desktopのインストール
- Docker Desktopのインストール  
  Dockerのアカウントを取得しておく（自分のメールアドレスで取っておく）  
  [Docker【公式】 Docker Desktop](https://www.docker.com/products/docker-desktop/)

  [Docker【公式】 インストール手順](https://docs.docker.jp/docker-for-windows/install.html)

## 1.3 Docker Desktopの起動 
- Docker Desktopの起動  
インストール完了後、デスクトップ上にDocker Desktopのショートカットアイコンが表示されると思うのでダブルクリックして起動する。タスクトレイにアイコンが表示されているかを確認する。

# 2. Docker環境構築

## 2.1 フォルダ作成
ドキュメントフォルダ等にDocker用のフォルダを作成する。  

C:\Users\\[ユーザ名]\Documents\Docker\xxxxxx\　…　xxxxx　というディレクトリ作成。  

## 2.2 ファイルを保存
設定ファイルを保存（Dockerディレクトリを参考）

## 2.3 実際にDockerをbuildする

自分のPC内でターミナルを起動。  

**Visual Studio code の場合**   
「ターミナル」　->　新しいターミナル（Ctrl+Shift+@）を起動

**Windows PowerShell の場合**

windowsの「スタートメニュー」　->　Windows PowerShell　-> Windows PowerShell をクリック

```bash:
#　ディレクトリを移動
cd .\Documents\Docker\

# Dockerイメージを作成
docker-compose build

# Dockerを起動
# -d でバックグランド起動
docker-compose up -d

# 起動しているコンテナが表示される
docker ps
```
Docker Desktopでイメージとコンテナが作成され、緑色になっていればOK。
