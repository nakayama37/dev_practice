<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録確認</title>
</head>
<body>
    <h1>{{ $user->name }}様</h1>
    <p>以下の内容でイベントアプリの登録が完了しました。</p>
    <br>
    ・ログインID: {{ $user->email }}<br>
    ・パスワード: {{ $password }}<br>
    ・URL: {{config('app.url')}}<br>
    @if($addFriendUrl)
    <p>イベントのリマインド通知をLineで受け取りたい方は以下の公式Lineを友達追加してください</p>
    <p>友達追加URL: {{ $addFriendUrl }}</p>
    @endif
    <p>ご登録ありがとうございます。</p>
</body>
</html>