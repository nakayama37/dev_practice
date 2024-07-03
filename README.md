# アプリ名：eventsite

## 概要
ファルコンカリキュラムのPHPコース、自作課題用ウェブサイトのリポジトリです

## 環境
### 言語
* HTML
* CSS
* PHP : 8.2
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