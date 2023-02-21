## docker環境の構築
```sh
docker-compose build
```

## docker環境の構築と立ち上げ
```sh
docker-compose up -d
```

## dockerの開始
```sh
docker-compose start
```

## dockerの停止
```sh
docker-compose stop
```

## イメージなどを完全削除（参考https://til.toshimaru.net/2022-02-05）
```sh
docker-compose down --rmi all --volumes --remove-orphans
```

## 立ち上がっているプロセスの確認
```sh
docker ps
```

## キャッシュの容量一覧
```sh
docker system df
```

## キャッシュ削除
```sh
docker builder prune
```
