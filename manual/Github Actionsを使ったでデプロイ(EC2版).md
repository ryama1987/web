# 1. 目的
Github Desktopでリモートリポジトリにpushした際、EC2サーバに自動でデプロイできるようにする。
まずは、開発環境のみの自動化を目指す。

# 2. 準備すること
1. AWSサーバにアクセスできるよう、公開鍵と秘密鍵を作る
2. GitHub上に、EC2サーバからGitHubへアクセスするための秘密鍵（1.で作成済）を登録する
3. GitHub Actionsを実行する際に必要となるサーバアクセス情報をSecretに登録する。
4. GitHub Actionsにjobを作成する（yml形式）

# 3. 作業内容
## (1) 公開鍵と秘密鍵の作成
```sh
# puttyでログイン後、sshの設定場所に移動 「~/」はホームディレクトリの意味。
$ cd ~/.ssh

# 鍵を作成
# -b でビットを指定　-Cはメールアドレスなど固有なもの　-f は生成時のファイル名の指定
# sampleは任意のリポジトリ名が分かりやすいかと思います。
$ ssh-keygen -t rsa -b 4096 -C mail2@xxxxx.co.jp -f id_rsa_sample

# 以下の項目はすべて、EnterキーでOKのよう。
# id_rsa_sample 、id_rsa_sample.pub が作成される。なお、id_rsa_sample が秘密鍵、id_rsa_sample.pub が公開鍵
Enter passphrase (empty for no passphrase): [Enter]
Enter same passphrase again: [Enter]

# .ssh/内にconfigファイルを作成
$ touch config
$ vim config

# 以下の内容を記載し、:wq。
# ほかの設定を追加する場合は、同じように追記していく。
Host github.com.sample
HostName github.com
User git
Port 22
IdentityFile ~/.ssh/id_rsa_sample
TCPKeepAlive yes
IdentitiesOnly yes

#ssh-agentを有効化・鍵の登録
$ eval `ssh-agent`
$ ssh-add ~/.ssh/id_rsa_sample
```
上記の情報を一通り登録し終えたら、SSH接続ができるかチェックをします。

```sh
$ ssh -T git@github.com.sample

#以下のように表示されたらOK
Hi yyy/zzz You've successfully authenticated, but GitHub does not provide shell access.
```

また、~/.ssh/configで、githubへのssh接続情報を設定したので、
サーバ上にあるgitのリモートリポジトリの情報を変更する必要があります。

```sh
# リポジトリがあるディレクトリに移動
$ cd /var/www/html/sample

# 今登録されているリモートリポジトリ名を確認
$ git remote -v

# sshのconfigで作ったhost名(git@github.com.xxxxx)、ユーザー名または組織名、リポジトリ名の形で設定する。
$ git remote set-url origin git@github.com.xxxxx:ユーザー名または組織名/リポジトリ名.git
```
## (1)の補足
ssh-agentは、何回も起動させると、プロセス増え続けてしまう。
```sh
# 以下の方法で、プロセスを削除する

#プロセスを確認
$ ps -aef | grep ssh-agent

# 以下のようPIDを指定
$ SSH_AGENT_PID=22743; export SSH_AGENT_PID

#プロセスをkillする
ssh-agent -k

```

## (2) GitHub上に秘密鍵を登録する
以下から、作成したid_rsa_sample.pubを登録する。  
https://github.com/[ユーザー名または所属組織名]/リポジトリ名/settings/keys

## (3) GitHub上にサーバアクセス情報を登録
以下から、必要なサーバアクセス情報を登録する。  
https://github.com/[ユーザー名または所属組織名]/リポジトリ名/secrets/actions

| secretsに登録する名称例 | 内容 |
| ---- | ---- |
| TEST_SECURITY_GP      | EC2サーバが属するセキュリティーグループid     |
| TEST_SERVER_USERNAME      |  EC2サーバにログインする際のユーザーネーム    | 
| TEST_SSH_PORT       |  EC2サーバにSSHでアクセスする際のポート番号    | 
| TEST_SSH_PRIVATE_KEY      | EC2サーバ立ち上げ時の秘密鍵     | 
| AWS_ACCESS_KEY       | IAMユーザー時に作成されるアクセスキー     | 
| AWS_SECRET_ACCESS_KEY       |  IAMユーザー時に作成される秘密アクセスキー    | 
| SERVER_DEPLOY_DIR      |  githubからデプロイする際のディレクトリ    | 

## (4)GitHubのActionsからファイルを作成
GitHub上にある任意のリポジトリに移動後、赤枠の「Actions」を選択すると以下の画面が表示されるので、「set up a workflow yourself」をクリック。

以下の画面が表示されるので、ファイル名を編集し、次に記載する内容をコピペし、「Start commit」をクリックしてアップする。

```yaml
name: Deploy to AWS DEV

on:
  push:
    branches:
      - develop

jobs:
  Test:
    name: deploy
    runs-on: ubuntu-latest

    steps:
      - name: echo1
        run: echo "run test"

      # IP取得ライブラリをインストール
      - name: Public IP Install
        id: ip
        uses: haythem/public-ip@v1.2

      - name: checkout
        uses: actions/checkout@v3

      - name: src check
        run: |
          ls -al
          cd .github/workflows/
          ls -al

      # AWS CLIをインストールする
      - name: AWS CLI install
        run: |
          curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
          unzip awscliv2.zip
          sudo ./aws/install --update
          aws --version

      # AWS CLIにキーを設定をする
      - name: AWS set Credentials
        uses: aws-actions/configure-aws-credentials@v1
        with:
          aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY }}
          aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          aws-region: ap-northeast-1
      # デプロイする
      - name: Deploy
        run: |

          # SSHのセキュリティグループを開放する
          aws ec2 authorize-security-group-ingress --group-id ${{ secrets.TEST_SECURITY_GP }} --protocol tcp --port 22 --cidr ${{ steps.ip.outputs.ipv4 }}/32

          # SSH接続して、git pullする
          echo "${{ secrets.TEST_SSH_PRIVATE_KEY }}" > private_key
          chmod 600 private_key

          # 鍵認証からgit操作
          ssh -o StrictHostKeyChecking=no ${{ secrets.TEST_SERVER_USERNAME }}@${{ secrets.TEST_SERVER_HOST }} -i private_key "cd ${{ secrets.SERVER_DEPLOY_DIR }}sample/ &&  git pull origin develop"
          
          # SSHのセキュリティグループを閉じる
          aws ec2 revoke-security-group-ingress --group-id ${{ secrets.TEST_SECURITY_GP }} --protocol tcp --port 22 --cidr ${{ steps.ip.outputs.ipv4 }}/32

```

登録後、実際にアップしたActionが動くので、問題があったら修正。  
緑色のステータスであれば成功となります。


# 99.参考記事
- [GitHub ActionsでEC2に自動デプロイ環境を構築する方法](https://note.com/ryoppei/n/nf2bf7ee78b0b)
- [GitHub ActionsでEC2へのデプロイ自動化(CI/CD)](https://gdtypk.com/2020-09-02-000000/)
- [【GitHub Actions】マージされたら自動でサーバー上にデプロイする方法](https://hsmtweb.com/tool-service/github-actions.html)
- [GitHub ActionでEC2にデプロイするまで(WindowsServer編)](https://qiita.com/senju797/items/cbc8716f10e547e5801f)
- [[備忘] 複数Githubアカウントでssh接続設定(config)を使い分ける手順](https://qiita.com/yampy/items/24638156abd383e08758)
- [【GitHub】複数GitHubアカウントのSSHキーを管理する。](https://zenn.dev/rabbit/articles/543355d334776b)
- [git@github.com: Permission denied (publickey)　が出たときの対処法](https://qiita.com/zukaishi/items/ae93487c4ceca1b12a8e)
- [~/.ssh/configについて](https://qiita.com/passol78/items/2ad123e39efeb1a5286b)
- [【SSH】ssh-agentの使い方を整理する](https://qiita.com/Yarimizu14/items/6a4bab703d67ea766ddc)
- [2017年版 SSH公開鍵認証で使用する秘密鍵ペアの作り方](https://qiita.com/wnoguchi/items/a72a042bb8159c35d056)


