# アプリ名：eventsite

## 概要
ファルコンカリキュラムのPHPコース、自作課題用ウェブサイトのリポジトリです

## 環境
### 言語
* HTML
* CSS
* PHP : 8.2 ※laravel10は、php v8以上が必要
* javascript
### フレームワーク
* Laravel：10.10
* Tailwind CSS
### 認証ライブラリ
* Laravel Breeze
## 環境変数(環境によって変更すべき箇所)※API関連の変数のみ
| 変数名                            | 概要                                          | 内容                                                                                      | 備考                                                              | 
| --------------------------------- | --------------------------------------------- | ----------------------------------------------------------------------------------------- | ----------------------------------------------------------------- | 
| LINE_LOGIN_CHANNEL_ID             | LINEログインチャンネルのID                    | LINED Developerコンソールで確認した値をコピぺ                                             | LINEのDeveloperコンソールで作成した、LINEログインチャンネルのもの | 
| LINE_LOGIN_CHANNEL_SECRET         | LINEログインチャンネルのチャネルシークレット  | 同上                                                                                      | 同上                                                              | 
| LINE_LOGIN_CALLBACK_URL           | LINEログインのコールバックURL                 | web.phpで設定したルートと同じものをコンソールで設定<br>例：http://localhost/line/callback | 同上                                                              | 
| LINE_MESSAGING_API_CHANNEL_ID     | LINEMessaging APIのチャンネルID               | LINED Developerコンソールで確認した値をコピぺ                                             | LINEのDeveloperコンソールで作成した、LINE Messaging APIのもの     | 
| LINE_MESSAGING_API_ACCESS_TOKEN   | LINEMessaging APIのチャンネルアクセストークン | 同上                                                                                      | 同上                                                              | 
| LINE_MESSAGING_API_CHANNEL_SECRET | LINEMessaging APIのチャンネルシークレット     | 同上                                                                                      | 同上                                                              | 
| LINE_ADD_FRIEND_URL              | 公式LINE友達追加URL                           | https://line.me/R/ti/p/{botのベーシックID}                                                | 同上                                                              | 
| STRIPE_PUBLIC_KEY                 | Stripeの公開キー                              | Stripeログインし、APIキーで確認した値                                                     | Stripeアカウントログインし、開発者のAPIキーのもの                 | 
| STRIPE_SECRET_KEY                 | Stripeのシークレットキー                      | 同上                                                                                      | 同上                                                              | 

## スタートガイド
### #1. 環境構築フォルダからファイルをダウンロードして下記を実行

```bash
docker-compose up --build -d
```
ファイルパス
```bash
ファルコンカリキュラム/カリキュラム元サイト作成/03_環境構築/docker-env.zip
```

### #2. ダウンロードしたファイルを開き、コンテナからリポジトリをクローンする

docker-compose.ymlと同じディレクトリで下記を実行
```bash
docker-compose exec php bash
```
```bash
git clone https://github.com/a-cial/falcon_curriculum_eventsite.git .
```
※cloneの際にusernameとpasswordを聞かれた場合、usernameは自分のアカウント名、passwordはアクセストークンを入れる

### #3. コンテナ内から、ComposerをInstall

```bash
composer install --prefer-dist
```

### #4. .envの生成

```bash
cp .env.example .env
```

### #5. データベースの作成
PHPMyAdmin(ポート番号はdocker-compose.ymlファイルに準ずる)
```bash
http://localhost:4040/
```
下記の名前でデータベースを作成（envに合わせる）
```bash
laravel
```

### #6. コンテナ内からテーブル作成（マイグレーションの実行）

```bash
php artisan migrate:fresh --seed
```

### #7. コンテナ内からStorage以下の権限を変更

```bash
chown www-data storage/ -R
```

### #8. コンテナ内からシンボリックリンクの作成

```bash
php artisan storage:link
```

### #9. npmのinstall

```bash
cd app
```
```bash
npm install
```
```bash
npm run build
```
## テストアカウント
利用者権限
```bash
test@test.com
pass123
```
主催者権限
```bash
manager@manager.com
pass123
```
オーナー権限
```bash
admin@admin.com
pass123
```