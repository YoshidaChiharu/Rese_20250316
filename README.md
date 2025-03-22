<div id="top"></div>

## 目次

1. [アプリケーション概要](#アプリケーション概要)
2. [アプリケーションURL](#アプリケーションURL)
3. [機能一覧](#機能一覧)
4. [使用技術一覧](#使用技術一覧)
5. [テーブル設計](#テーブル設計)
6. [ER図](#ER図)
7. [環境構築手順](#環境構築手順)
8. [補足事項](#補足事項)

## アプリケーション概要

アプリケーション名 : Rese（リーズ）<br>
概要：飲食店予約サービス<br>
![Rese_top](/Rese_top.png)

## アプリケーションURL

- 開発環境：[http://localhost/](http://localhost/)
    - phpMyAdmin：[http://localhost:8888/](http://localhost:8888/)
- 本番環境：[http://ec2-57-180-170-88.ap-northeast-1.compute.amazonaws.com](http://ec2-57-180-170-88.ap-northeast-1.compute.amazonaws.com)

## 機能一覧

- 会員登録（メール認証）
- ログイン（メール認証）
- ログアウト
- ユーザー情報取得
- ユーザー飲食店お気に入り一覧取得
- ユーザー飲食店予約情報取得
- 飲食店一覧取得
- 飲食店詳細取得
- 飲食店お気に入り追加
- 飲食店お気に入り削除
- 飲食店予約情報追加
- 飲食店予約情報削除
- 飲食店予約情報変更
- エリアで検索する
- ジャンルで検索する
- 店名で検索する
- 5段階での飲食店評価
- 口コミ投稿
- 予約QRコード表示
- 予約時の事前決済（Stripeを使用したクレジットカード決済）
- リマインダーメール送信（予約当日のAM10:00に一斉送信）
- 管理画面表示\
↓ 以下管理画面機能
- 管理者 : 店舗代表者のアカウント作成
- 管理者 : 利用者へ向けたお知らせメールの一斉送信
- 店舗代表者 : 新規店舗情報の登録
- 店舗代表者 : 店舗情報の編集
- 店舗代表者 : 自店舗の予約一覧参照
- 店舗代表者 : 予約の詳細情報の参照
- 店舗代表者 : 予約情報の編集
- 店舗代表者 : 予約情報の削除（決済済み予約の場合は同時に返金処理を実行）

## 使用技術一覧

| 言語・フレームワーク  | バージョン |
| --------------------- | ---------- |
| Laravel               | 11.27.2    |
| PHP                   | 8.3.7      |
| NGINX                 | 1.26.0     |
| MySQL                 | 8.0.37     |

## テーブル設計

![Rese_tables](/Rese_tables.png)

## ER図

![er_rese](/er_rese.png)

## 環境構築手順

1. **Dockerテンプレートのリモートリポジトリをクローンする**
```
git clone git@github.com:YoshidaChiharu/docker-template-LEMP-supervisor-cron.git
```
2. **ディレクトリを移動し、srcディレクトリを作成**
```
cd docker-template-LEMP-supervisor-cron
```
```
mkdir src
```
3. **ディレクトリを移動し、本プロジェクトのリモートリポジトリをクローンする**
```
cd src
```
```
git clone git@github.com:YoshidaChiharu/Rese_20250316.git
```
4. **ディレクトリ名を変更**
```
sudo mv Rese_20250316 Rese
```
5. **ディレクトリを移動し、Dockerコンテナを作成**
```
cd ..
```
```
docker-compose up -d --build
```
6. **`composer install` コマンドでパッケージをインストール**
```
docker-compose exec php-fpm bash
```
```
composer install --ignore-platform-req=ext-bcmath
```
7. **.envファイルを作成**
```
cp .env.local .env
```
8. **アプリケーションキーを生成**
```
php artisan key:generate
```
9. **シンボリックリンクの作成**
```
php artisan storage:link
```
10. **テーブル作成**
```
php artisan migrate
```
11. **ダミーデータ作成**
```
php artisan db:seed
```
12. **パーミッションを変更**
```
chmod -R 777 *
```
```
chmod -R 2777 storage/
```
13. **Dockerコンテナを再起動**
```
exit
```
```
docker-compose restart
```

## 補足事項

- 動作確認用アカウント情報
    - 管理者アカウント\
        メールアドレス：admin@example.com\
        パスワード：password
    - 店舗代表者のダミーアカウント\
        メールアドレス：dummy_shop_owner@example.com\
        パスワード：password\
    ※上記２つのアカウントのみメール認証無しでログイン出来るようにしております。動作確認にご使用下さい
- Stripeテスト用クレジットカード\
    [https://docs.stripe.com/testing?locale=ja-JP#cards](https://docs.stripe.com/testing?locale=ja-JP#cards)
- Pro入会テスト追加機能に関する補足説明
    - 口コミ機能
        - 来店日時を過ぎた予約履歴が有る場合のみ口コミ可能となっております
        - 以下手順にて確認可能です
          1.一般ユーザーアカウントで任意店舗に予約を登録する
          2.店舗代表者ダミーアカウントにて管理ページにアクセス（メニュー内「administration page」項目）
          3.「予約一覧」から該当店舗を選択
          4.予約カレンダーから予約日を選び、下部「タイムスケジュール」にて該当予約の詳細ページに遷移（左端「予約1」などから遷移可能）
          5.「編集」にて予約を過去の日付に変更
          6.手順1のアカウントに戻し、該当店舗の詳細ページに遷移（マイページの「来店履歴」からも遷移可能）
        - 店舗の評価値については、「食べログ」等の仕様を参考に "3.00" を基準値して徐々に変動する形で実装しております（☆5評価を一つ入れただけで店舗評価が "5.00" とはならない）
    - 店舗一覧ソート機能
        - ランダムソートについては、ページネーション中（2/4ページ目表示中など）のみ並びを固定するように実装しております（ページ切り替え後に同じ店舗が表示される事を防ぐ為）
    - csvインポート機能
        - 管理者アカウントでログイン後のメニュー＞「administration page」＞「店舗一括登録」ページに実装しております
          [http://localhost/admin/register_shop_from_csv](http://localhost/admin/register_shop_from_csv)
        - プロジェクトフォルダ配下に以下動作確認用ファイルを用意しておりますのでご活用ください
          shop_data_test.csv        ※通常動作確認用（10店舗一括登録）
          shop_data_error.csv       ※バリデーションエラー確認用（多数記述ミス）
          shop_data_error_02.csv    ※バリデーションエラー確認用（店舗情報なし）
          shop_image_01.jpg
          shop_image_02.jpg
          shop_image_03.jpg
          shop_image_04.jpg
          shop_image_05.jpg
          shop_image_06.jpg
          shop_image_07.jpg
          shop_image_08.jpg
          shop_image_09.jpg
          shop_image_10.jpg

<p align="right">(<a href="#top">トップへ</a>)</p>
